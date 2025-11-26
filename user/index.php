<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lẩu Hương Vị - Trang Chủ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'php/header.php'; ?>
    <main>
        <section class="banner">
            <div class="slider-wrapper">
                <div class="slider">
                    <div class="slide">
                        <img src="../images/banner1.png" alt="#">
                    </div>
                    <div class="slide">
                        <img src="../images/banner2.png" alt="#">
                    </div>
                </div>
                <button class="slider-btn" id="prevBtn">&lt;</button>
                <button class="slider-btn" id="nextBtn">&gt;</button>
            </div>
        </section>
        <section id="menu-preview" class="menu-preview">
            <div class="container">
                <h2>GỢI Ý MÓN ĂN</h2>
                <div class="menu-grid">
            <?php
            $sql = "SELECT * FROM mon_an ORDER BY RAND() LIMIT 3";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {                   
                    $img_path = "../images/" . $row['anh'];
            ?>
            <div class="menu-item">
                <a href="menu.php">
                <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($row['ten_mon_an']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                <h3><?php echo htmlspecialchars($row['ten_mon_an']); ?></h3>
            </a>                   
            <p>
            <?php 
                $mo_ta = htmlspecialchars($row['mo_ta']);
                if (strlen($mo_ta) > 50) echo substr($mo_ta, 0, 50) . '...';
                else echo $mo_ta;
            ?>
            </p>                   
                <span class="price"><?php echo number_format($row['gia'], 0, ',', '.'); ?> VNĐ</span>
            </div>
            <?php
                } 
                } else {
                    echo "<p style='text-align:center; width:100%'>Chưa có món ăn nổi bật nào.</p>";
                }
            ?>
            </div>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="menu.php" class="cta-button">Xem Toàn Bộ Thực Đơn</a>
                </div>
            </div>
        </section>
    </main>
    <?php include 'php/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>