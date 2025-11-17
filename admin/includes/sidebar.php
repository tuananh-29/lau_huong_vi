<?php
// Lưu tại: /admin/includes/sidebar.php
// Lấy tên file hiện tại để active menu
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>Trang Quản Trị</h2>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Bảng Điều Khiển
                </a>
            </li>
            
            <li><hr style="border-color: #34495e;"></li>

            <!-- Các link chức năng bạn yêu cầu -->
            <li>
                <a href="manage_orders.php" class="<?php echo ($current_page == 'manage_orders.php') ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice"></i> Thống kê Hóa Đơn
                </a>
            </li>
            <li>
                <a href="manage_reservations.php" class="<?php echo ($current_page == 'manage_reservations.php') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check"></i> Quản lý Đặt Bàn
                </a>
            </li>
            <li>
                <a href="manage_tables.php" class="<?php echo ($current_page == 'manage_tables.php') ? 'active' : ''; ?>">
                    <i class="fas fa-chair"></i> Quản lý Bàn
                </a>
            </li>
            <li>
                <a href="manage_menu.php" class="<?php echo ($current_page == 'manage_menu.php') ? 'active' : ''; ?>">
                    <i class="fas fa-utensils"></i> Quản lý Thực Đơn
                </a>
            </li>
            
            <li><hr style="border-color: #34495e;"></li>
            
            <li>
                <a href="manage_inventory.php" class="<?php echo ($current_page == 'manage_inventory.php') ? 'active' : ''; ?>">
                    <i class="fas fa-boxes"></i> Quản lý Kho
                </a>
            </li>
            <li>
                <a href="manage_ingredients.php" class="<?php echo ($current_page == 'manage_ingredients.php') ? 'active' : ''; ?>">
                    <i class="fas fa-seedling"></i> Quản lý Nguyên Liệu
                </a>
            </li>
            <li>
                <a href="manage_stock_in.php" class="<?php echo ($current_page == 'manage_stock_in.php') ? 'active' : ''; ?>">
                    <i class="fas fa-cart-plus"></i> Quản lý Nhập Kho
                </a>
            </li>
            <li>
                <a href="manage_stock_out.php" class="<?php echo ($current_page == 'manage_stock_out.php') ? 'active' : ''; ?>">
                    <i class="fas fa-cart-arrow-down"></i> Quản lý Xuất Kho
                </a>
            </li>

            <li><hr style="border-color: #34495e;"></li>

            <li>
                <a href="manage_customers.php" class="<?php echo ($current_page == 'manage_customers.php') ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Quản lý Khách Hàng
                </a>
            </li>
            <li>
                <a href="manage_staff.php" class="<?php echo ($current_page == 'manage_staff.php') ? 'active' : ''; ?>">
                    <i class="fas fa-user-shield"></i> Quản lý Nhân Sự
                </a>
            </li>
            
            <li><hr style="border-color: #34495e;"></li>

            <li>
                <a href="report_revenue.php" class="<?php echo ($current_page == 'report_revenue.php') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i> Báo cáo Doanh Thu
                </a>
            </li>
        </ul>
    </nav>
</aside>