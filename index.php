<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lẩu Hương Vị - Trang Chủ</title>
    <!-- Liên kết tới CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php 
        // Nhúng phần đầu trang (header) vào đây
        include 'php/header.php'; 
    ?>

    <!-- ==================== MAIN (NỘI DUNG CHÍNH) ==================== -->
    <main>
        <!-- MỤC 1: BANNER DI CHUYỂN -->
        <section class="banner">
            <div class="slider-wrapper">
                <div class="slider">
                    <div class="slide">
                        <img src="https://placehold.co/1200x400/C06A4F/F7F3E8?text=Lẩu+Thái+Tomyum" alt="Banner Lẩu Thái">
                    </div>
                    <div class="slide">
                        <img src="https://placehold.co/1200x400/3D3532/F7F3E8?text=Lẩu+Bò+Sa+Tế" alt="Banner Lẩu Bò">
                    </div>
                    <div class="slide">
                        <img src="https://placehold.co/1200x400/F7F3E8/3D3532?text=Combo+Hải+Sản+Tươi" alt="Banner Hải Sản">
                    </div>
                </div>
                <!-- Nút điều khiển (tùy chọn) -->
                <button class="slider-btn" id="prevBtn">&lt;</button>
                <button class="slider-btn" id="nextBtn">&gt;</button>
            </div>
        </section>

        <!-- MỤC 2: THỰC ĐƠN NỔI BẬT -->
        <section id="menu-preview" class="menu-preview">
            <div class="container">
                <h2>Thực Đơn Nổi Bật</h2>
                <div class="menu-grid">
                    <!-- Món 1 -->
                    <div class="menu-item">
                        <a href="menu.php"> <!-- Link tới trang thực đơn -->
                            <img src="https://placehold.co/300x200/C06A4F/FFFFFF?text=Lẩu+Bò" alt="Lẩu Bò Sa Tế">
                            <h3>Lẩu Bò Sa Tế</h3>
                        </a>
                        <p>Nước lẩu đậm đà vị sa tế cay nồng.</p>
                        <span class="price">250.000 VNĐ</span>
                    </div>
                    <!-- Món 2 -->
                    <div class="menu-item">
                        <a href="menu.php">
                            <img src="https://placehold.co/300x200/C06A4F/FFFFFF?text=Lẩu+Hải+Sản" alt="Lẩu Hải Sản">
                            <h3>Lẩu Hải Sản Tươi</h3>
                        </a>
                        <p>Nước lẩu chua cay với hải sản tươi sống.</p>
                        <span class="price">300.000 VNĐ</span>
                    </div>
                    <!-- Món 3 -->
                    <div class="menu-item">
                        <a href="menu.php">
                            <img src="https://placehold.co/300x200/C06A4F/FFFFFF?text=Lẩu+Nấm" alt="Lẩu Nấm">
                            <h3>Lẩu Nấm</h3>
                        </a>
                        <p>Lẩu nấm thanh ngọt, tốt cho sức khỏe.</p>
                        <span class="price">230.000 VNĐ</span>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="menu.php" class="cta-button">Xem Toàn Bộ Thực Đơn</a>
                </div>
            </div>
        </section>
    </main>

    <?php 
        // Nhúng phần chân trang (footer) vào đây
        include 'php/footer.php'; 
    ?>

    <!-- Liên kết tới JavaScript -->
    <script src="js/script.js"></script>

</body>
</html>