<?php
    // LUÔN LUÔN bắt đầu session ở đầu tệp
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // THÊM MỚI: Đếm số lượng sản phẩm trong giỏ hàng giống CODE 1
    $cart_item_count = 0;
    if (!empty($_SESSION['cart'])) {
        $cart_item_count = array_sum($_SESSION['cart']);
    }
?>
<header class="header">
    <div class="container">
        <div class="logo">
            <!-- SỬA: Trở về index.php giống CODE 1 -->
            <a href="../index.php">Lẩu Hương Vị</a> 
        </div>

        <nav class="navigation">
            <ul>
                <!-- GIỮ NGUYÊN NHƯ CODE 1 -->
                <li><a href="../index.php">Trang Chủ</a></li>
                <li><a href="../menu.php">Thực Đơn</a></li>
                <li><a href="../about.php">Về Chúng Tôi</a></li>
                <li><a href="../contact.php">Liên Hệ</a></li>

                <?php
                    // Xác định link đặt bàn giống CODE 1
                    if (isset($_SESSION['user_id'])) {
                        $reservation_link = '../reservation.php';
                    } else {
                        $reservation_link = 'php/login.php';
                    }

                    echo '<li><a href="' . $reservation_link . '">Đặt Bàn</a></li>';
                ?>
            </ul>
        </nav>

        <div class="header-action">

            <!-- THÊM MỚI: Icon giỏ hàng giống CODE 1 -->
            <a href="../cart.php" class="profile-link" title="Giỏ hàng" style="font-size: 20px; text-decoration: none;">
                🛒<span class="cart-count" style="font-size: 14px; font-weight: bold; color: var(--color-primary);">
                    (<?php echo $cart_item_count; ?>)
                </span>
            </a>

            <?php
                if (isset($_SESSION['user_id']) && isset($_SESSION['full_name'])) {
                    // Đã đăng nhập:

                    // Icon user giống CODE 1
                    echo '<a href="../profile.php" class="profile-link" title="Tài khoản của tôi">👤</a>';

                    // Lời chào
                    echo '<span class="welcome-user">Chào, ' . htmlspecialchars($_SESSION['full_name']) . '!</span>';

                    // Đăng xuất
                    echo '<a href="php/logout.php" class="cta-button-secondary">Đăng Xuất</a>';

                    // Link admin giống CODE 1
                    if ($_SESSION['role'] == 'admin') {
                        echo '<a href="../admin/index.php" class="cta-button">Trang Admin</a>';
                    }

                } else {
                    // Chưa đăng nhập
                    echo '<a href="php/login.php" class="cta-button-secondary">Đăng Nhập</a>';
                    echo '<a href="php/register.php" class="cta-button">Đăng Ký</a>';
                }
            ?>
        </div>
    </div>
</header>
