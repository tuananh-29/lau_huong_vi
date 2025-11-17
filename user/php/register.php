<?php
session_start();
include '../config/db.php';
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['captcha']) || strtolower($_POST['captcha']) != strtolower($_SESSION['captcha_code'])) {
        $message = '<div class="message error">Mã Captcha không chính xác!</div>';
    } else {
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password']; 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_check = "SELECT ma_nguoi_dung FROM nguoi_dung WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $message = '<div class="message error">Email này đã được sử dụng!</div>';
        } else {
            $role = 'khachhang';
            $sql_insert = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, vai_tro) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssss", $full_name, $email, $hashed_password, $role);
            
            if ($stmt_insert->execute()) {
                $message = '<div class="message success">Đăng ký thành công! Bạn có thể đăng nhập ngay.</div>';
                header("refresh:2;url=login.php");
            } else {
                $message = '<div class="message error">Đã có lỗi xảy ra: ' . $conn->error . '</div>';
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
        $conn->close();
    }
} 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Lẩu Hương Vị</title>
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <aside class="auth-sidebar">
            <div class="logo">
                <img src="../images/logo-alt.png" alt="RESTARANT THE DREAMERS">
            </div>
            <h2>Chào mừng đến với nhà hàng Lẩu Hương Vị</h2>
            <p>Để sử dụng dịch vụ vui lòng đăng nhập với tài khoản cá nhân của bạn</p>
            <a href="login.php" class="cta-button-outline"><< Đăng nhập</a>
        </aside>
        <section class="auth-form-section">
            <h1>TẠO TÀI KHOẢN</h1>
            <?php echo $message; ?>
            <form method="POST" action="register.php">
                <div class="auth-form-group">
                    <div class="auth-input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="full_name" name="full_name" class="auth-input" placeholder="Họ và Tên" required>
                    </div>
                </div>   
                <div class="auth-form-group">
                    <div class="auth-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="auth-input" placeholder="Email" required>
                    </div>
                </div>
                <div class="auth-form-group">
                    <div class="auth-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="auth-input" placeholder="Mật khẩu" required>
                    </div>
                </div>
                <div class="auth-form-group">
                    <div class="captcha-group">
                        <input type="text" id="captcha" name="captcha" class="auth-input" placeholder="Captcha" required>
                        <img src="captcha_image.php" alt="Captcha"
                             onclick="this.src='captcha_image.php?' + new Date().getTime()">
                    </div>
                </div>
                <button type="submit" class="auth-button">ĐĂNG KÝ</button>
            </form>
            <p class="auth-switch-link">
                Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
            </p>
        </section>
    </div>
</body>
</html>