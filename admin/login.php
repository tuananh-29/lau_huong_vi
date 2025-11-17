<?php
// Lưu tại: /admin/login.php
session_start();

// Nếu đã đăng nhập admin, chuyển tới dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Đường dẫn này lùi ra 1 cấp để tìm thư mục config/
include '../config/db.php'; 
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = $_POST['password'];

    $sql = "SELECT ma_nguoi_dung, ho_ten, mat_khau, vai_tro FROM nguoi_dung WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // KIỂM TRA MẬT KHẨU VÀ VAI TRÒ
        // BỎ MÃ HÓA: Thay "password_verify" bằng so sánh "=="
        if ($password_input == $user['mat_khau']) {
            
            if ($user['vai_tro'] == 'admin' || $user['vai_tro'] == 'nhanvien') {
                // Đăng nhập thành công
                $_SESSION['admin_id'] = $user['ma_nguoi_dung'];
                $_SESSION['admin_name'] = $user['ho_ten'];
                $_SESSION['admin_role'] = $user['vai_tro'];
                
                header("Location: index.php"); // Chuyển đến trang dashboard
                exit();
            } else {
                // Là khách hàng, không có quyền
                $message = '<div class="message error">Bạn không có quyền truy cập!</div>';
            }
        } else {
            $message = '<div class="message error">Sai email hoặc mật khẩu!</div>';
        }
    } else {
        $message = '<div class="message error">Sai email hoặc mật khẩu!</div>';
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Trị</title>
    <!-- Link đến file CSS admin -->
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body style="background-color: var(--admin-bg);">

    <div class="login-container">
        <h1>Đăng Nhập Admin</h1>
        
        <?php echo $message; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="password">Mật Khẩu</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="form-button">Đăng Nhập</button>
        </form>
    </div>

</body>
</html>