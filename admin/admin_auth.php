<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
function check_admin_role() {
    if ($_SESSION['admin_role'] != 'admin') {
        return false;
    }
    return true;
}
?>