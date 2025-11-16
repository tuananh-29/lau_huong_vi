<?php
    // LUÔN LUÔN bắt đầu session ở đầu tệp
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
<header class="header">
    <div class="container">
        <div class="logo">
            <a href="../index.php">Lẩu Hương Vị</a> 
        </div>
        <nav class="navigation">
            <ul>
                <li><a href="../menu.php">Thực Đơn</a></li>
                <li><a href="../about.php">Về Chúng Tôi</a></li>
                <li><a href="../contact.php">Liên Hệ</a></li>
                
                <?php
                    
                    // 1. Xác định link đặt bàn
                    $reservation_link = '';
                    if (isset($_SESSION['user_id'])) {
                        // SỬA 3: Link đến reservation.php (cùng thư mục)
                        $reservation_link = 'reservation.php';
                    } else {
                        // SỬA 4: Link đến login.php (cùng thư mục)
                        $reservation_link = 'login.php';
                    }
                    
                    // 2. Luôn hiển thị link "Đặt Bàn"
                    echo '<li><a href="' . $reservation_link . '">Đặt Bàn</a></li>';
                ?>
            </ul>
        </nav>
        <div class="header-action">
            <?php
                // Kiểm tra xem session 'user_id' có tồn tại không
                if (isset($_SESSION['user_id']) && isset($_SESSION['full_name'])) {
                    // Đã đăng nhập:
                    
                    // ========== YÊU CẦU CỦA BẠN (THÊM ICON) ==========
                    // Thêm link icon người dùng 👤 trỏ tới profile.php
                    echo '<a href="profile.php" class="profile-link" title="Tài khoản của tôi">👤</a>';
                    // ===================================================

                    // Hiển thị lời chào
                    echo '<span class="welcome-user">Chào, ' . htmlspecialchars($_SESSION['full_name']) . '!</span>';
                    
                    
                    echo '<a href="php/logout.php" class="cta-button-secondary">Đăng Xuất</a>';
                    
                    if ($_SESSION['role'] == 'admin') {
                        echo '<a href="admin_dashboard.php" class="cta-button">Trang Admin</a>';
                    }

                } else {
                    // Chưa đăng nhập:
                    // SỬA 6: Link đến login.php và register.php (cùng thư mục)
                    echo '<a href="php/login.php" class="cta-button-secondary">Đăng Nhập</a>';
                    echo '<a href="php/register.php" class="cta-button">Đăng Ký</a>';
                }
            ?>
        </div>
    </div>
</header>