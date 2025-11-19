<?php
session_start();
include '../../config/db.php'; 
include 'header.php';
$cart_products = array();
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    $sql = "SELECT ma_mon_an, ten_mon_an, gia, anh FROM mon_an WHERE ma_mon_an IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart_products[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Lẩu Hương Vị</title>
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
    <main>
        <section class="menu-page">
            <div class="container">
                <h2>Giỏ Hàng Của Bạn</h2>
                <?php if (empty($cart_products)): ?>
                    <p style="text-align: center;">Giỏ hàng của bạn đang trống. <a href="../menu.php">Quay lại thực đơn</a></p>
                <?php else: ?>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th colspan="2">Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tạm tính</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_products as $product): ?>
                                <?php
                                $product_id = $product['ma_mon_an'];
                                $quantity = $_SESSION['cart'][$product_id];
                                $subtotal = $product['gia'] * $quantity;
                                $total_price += $subtotal;
                                $image_path = '../' . htmlspecialchars($product["anh"]);
                                $placeholder_image = 'https://placehold.co/100x100/eee/ccc?text=No+Image';
                                ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['ten_mon_an']); ?>" 
                                             onerror="this.src='<?php echo $placeholder_image; ?>';">
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($product['ten_mon_an']); ?>
                                    </td>
                                    <td><?php echo number_format($product['gia']); ?> VNĐ</td>
                                    <td>
                                        <form action="cart_action.php" method="POST" class="cart-update-form">
                                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                            <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="0" class="form-input">
                                            <button type="submit" name="update_cart" class="cta-button-secondary">Cập nhật</button>
                                        </form>
                                    </td>
                                    <td><?php echo number_format($subtotal); ?> VNĐ</td>
                                    <td>
                                        <form action="cart_action.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                            <button type="submit" name="remove_from_cart" class="cta-button" style="background-color: #c9302c;">X</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="cart-summary">
                        <h3>Tổng cộng: <span class="price"><?php echo number_format($total_price); ?> VNĐ</span></h3>
                        <a href="checkout.php" class="cta-button">Tiến hành Thanh Toán</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>