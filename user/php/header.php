<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $current_script = $_SERVER['PHP_SELF'];
    $path_prefix = '';
    
    if (strpos($current_script, '/php/') !== false) {
        $path_prefix = '../';
    }

    $cart_item_count = 0;
    if (!empty($_SESSION['cart'])) {
        $cart_item_count = array_sum($_SESSION['cart']);
    }
?>
<header class="header">
    <div class="container">
        <div class="logo">
            <a href="<?php echo $path_prefix; ?>index.php">Lẩu Hương Vị</a> 
        </div>
        <nav class="navigation">
            <ul>
                <li><a href="<?php echo $path_prefix; ?>index.php">Trang Chủ</a></li>
                <li><a href="<?php echo $path_prefix; ?>menu.php">Thực Đơn</a></li>
                <li><a href="<?php echo $path_prefix; ?>about.php">Về Chúng Tôi</a></li>
                <li><a href="<?php echo $path_prefix; ?>contact.php">Liên Hệ</a></li>
                <?php
                    if (isset($_SESSION['user_id'])) {
                        $reservation_link = $path_prefix . 'reservation.php';
                    } else {
                        $reservation_link = $path_prefix . 'php/login.php';
                    }
                    echo '<li><a href="' . $reservation_link . '">Đặt Bàn</a></li>';
                ?>
            </ul>
        </nav>
        <div class="header-action">
            <a href="<?php echo $path_prefix; ?>php/cart.php" class="profile-link" title="Giỏ hàng" style="font-size: 20px; text-decoration: none;">
                🛒<span class="cart-count" style="font-size: 14px; font-weight: bold; color: var(--color-primary);">
                    (<?php echo $cart_item_count; ?>)
                </span>
            </a>
            <?php
                if (isset($_SESSION['user_id']) && isset($_SESSION['full_name'])) {
                    echo '<a href="' . $path_prefix . 'profile.php" class="profile-link" title="Tài khoản của tôi">👤</a>';
                    echo '<span class="welcome-user">Chào, ' . htmlspecialchars($_SESSION['full_name']) . '!</span>';
                    echo '<a href="' . $path_prefix . 'php/logout.php" class="cta-button-secondary">Đăng Xuất</a>';
                    
                    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                        echo '<a href="' . $path_prefix . '../admin/index.php" class="cta-button">Trang Admin</a>';
                    }
                } else {
                    echo '<a href="' . $path_prefix . 'php/login.php" class="cta-button-secondary">Đăng Nhập</a>';
                    echo '<a href="' . $path_prefix . 'php/register.php" class="cta-button">Đăng Ký</a>';
                }
            ?>
        </div>
    </div>
</header>