<?php
// Lưu tại: /admin/includes/header.php
// File này đã giả định admin_auth.php đã được include
?>
<header class="header">
    <div class="user-info">
        <span>Chào, <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['admin_role']); ?>)</span>
        <a href="logout.php" class="logout-link">Đăng Xuất</a>
    </div>
</header>