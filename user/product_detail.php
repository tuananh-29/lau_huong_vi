<?php
session_start();
include '../config/db.php';
include 'php/header.php';
$product_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM mon_an WHERE ma_mon_an = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    echo "<div class='container' style='padding:50px; text-align:center;'>";
    echo "<h2>Không tìm thấy món ăn này!</h2>";
    echo "<a href='menu.php' class='cta-button'>Quay lại thực đơn</a>";
    echo "</div>";
    include 'php/footer.php';
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['ten_mon_an']); ?> - Lẩu Hương Vị</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <div class="container">
            <div style="margin-top: 20px;">
                <a href="menu.php" style="text-decoration: none; color: #555;">&larr; Quay lại thực đơn</a>
            </div>

            <div class="product-detail-container">
                <div class="product-image">
                    <?php 
                        $img_path = "../images/" . $product['anh'];
                        $placeholder = "https://placehold.co/600x400?text=No+Image";
                    ?>
                    <img src="<?php echo $img_path; ?>" 
                         onerror="this.src='<?php echo $placeholder; ?>'" 
                         alt="<?php echo htmlspecialchars($product['ten_mon_an']); ?>">
                </div>
                
                <div class="product-info">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['ten_mon_an']); ?></h1>
                    
                    <p class="product-price">
                        <?php echo number_format($product['gia'], 0, ',', '.'); ?> VNĐ
                    </p>
                    
                    <div class="product-desc">
                        <p><strong>Mô tả:</strong></p>
                        <p><?php echo nl2br(htmlspecialchars($product['mo_ta'])); ?></p>
                    </div>

                    <form action="php/cart_action.php" method="POST" class="ajax-cart-form add-cart-section">
                        <input type="hidden" name="product_id" value="<?php echo $product['ma_mon_an']; ?>">
                        
                        <label for="quantity" style="font-weight:bold;">Số lượng:</label>
                        <input type="number" id="quantity" name="quantity" class="qty-input" value="1" min="1" max="50">
                        
                        <button type="submit" name="add_to_cart" class="cta-button" style="border:none; cursor:pointer;">
                            Thêm Vào Giỏ Hàng
                        </button>
                    </form>
                    
                    <?php if ($product['trang_thai'] == 'het_hang'): ?>
                        <p style="color: red; font-weight: bold; margin-top: 15px;">Món này hiện đang tạm hết hàng</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'php/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>