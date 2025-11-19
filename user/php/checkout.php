<?php
session_start();
include '../../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (empty($_SESSION['cart'])) {
    header("Location: ../menu.php");
    exit();
}
$message = '';
$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_nguoi_nhan = mysqli_real_escape_string($conn, $_POST['ten_nguoi_nhan']);
    $sdt_nguoi_nhan = mysqli_real_escape_string($conn, $_POST['sdt_nguoi_nhan']);
    $dia_chi_giao_hang = mysqli_real_escape_string($conn, $_POST['dia_chi_giao_hang']);
    $ghi_chu = mysqli_real_escape_string($conn, $_POST['ghi_chu']);
    $product_ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    $sql_products = "SELECT ma_mon_an, gia FROM mon_an WHERE ma_mon_an IN ($placeholders)";
    $stmt_products = $conn->prepare($sql_products);
    $stmt_products->bind_param($types, ...$product_ids);
    $stmt_products->execute();
    $result_products = $stmt_products->get_result();
    $products_in_db = array();
    $tong_tien = 0;
    
    while ($row = $result_products->fetch_assoc()) {
        $products_in_db[$row['ma_mon_an']] = $row['gia'];
    }
    foreach ($cart as $product_id => $quantity) {
        if (isset($products_in_db[$product_id])) {
            $tong_tien += $products_in_db[$product_id] * $quantity;
        }
    }
    $conn->begin_transaction();
    try {
        $sql_order = "INSERT INTO don_hang (ma_nguoi_dung, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao_hang, tong_tien, ghi_chu, tran_thai) 
                      VALUES (?, ?, ?, ?, ?, ?, 'cho_xac_nhan')";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("isssis", $user_id, $ten_nguoi_nhan, $sdt_nguoi_nhan, $dia_chi_giao_hang, $tong_tien, $ghi_chu);
        $stmt_order->execute();
        $ma_don_hang = $conn->insert_id;
        $sql_detail = "INSERT INTO chi_tiet_don (ma_don_hang, ma_mon_an, so_luong, gia) VALUES (?, ?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);
        foreach ($cart as $product_id => $quantity) {
            if (isset($products_in_db[$product_id])) {
                $gia_mon = $products_in_db[$product_id];
                $stmt_detail->bind_param("iiii", $ma_don_hang, $product_id, $quantity, $gia_mon);
                $stmt_detail->execute();
            }
        }
        $conn->commit();
        unset($_SESSION['cart']);
        $message = '<div class="message success">Đặt hàng thành công! Cảm ơn bạn đã mua hàng.</div>';
        header("refresh:3;url=index.php");
    } catch (Exception $e) {
        $conn->rollback();
        $message = '<div class="message error">Đã có lỗi xảy ra khi đặt hàng: ' . $e->getMessage() . '</div>';
    }
    $stmt_products->close();
    $stmt_order->close();
    $stmt_detail->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - Lẩu Hương Vị</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="auth-page">
            <div class="container">
                <h2>Thông Tin Giao Hàng</h2>
                
                <?php echo $message; ?>

                <?php if (!empty($_SESSION['cart'])): ?>
                <div class="auth-form-container">
                    <form method="POST" action="checkout.php">
                        <div class="form-group">
                            <label for="ten_nguoi_nhan">Họ và Tên Người Nhận</label>
                            <input type="text" id="ten_nguoi_nhan" name="ten_nguoi_nhan" class="form-input" value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="sdt_nguoi_nhan">Số Điện Thoại</label>
                            <input type="tel" id="sdt_nguoi_nhan" name="sdt_nguoi_nhan" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="dia_chi_giao_hang">Địa Chỉ Giao Hàng</label>
                            <input type="text" id="dia_chi_giao_hang" name="dia_chi_giao_hang" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="ghi_chu">Ghi Chú (Tùy chọn)</label>
                            <textarea id="ghi_chu" name="ghi_chu" class="form-textarea"></textarea>
                        </div>
                        <button type="submit" class="cta-button form-button">Xác Nhận Đặt Hàng</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>