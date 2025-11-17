<?php
?>
<header class="header">
    <div class="user-info">
        <span>Chào, <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['admin_role']); ?>)</span>
        <a href="logout.php" class="logout-link">Đăng Xuất</a>
    </div>
</header>