<?php
// Lưu tại: /admin/admin_auth.php
// File này sẽ được include ở ĐẦU MỖI TRANG admin
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Nếu chưa đăng nhập, đá về trang login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// (Tùy chọn) Kiểm tra quyền admin cho các trang đặc biệt
function check_admin_role() {
    if ($_SESSION['admin_role'] != 'admin') {
        // Nếu không phải admin, có thể đá về dashboard
        // echo "Bạn không có quyền thực hiện hành động này!";
        // exit();
        return false;
    }
    return true;
}
?>