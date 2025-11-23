<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include '../../config/db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['captcha']) || strtolower($_POST['captcha']) != strtolower($_SESSION['captcha_code'])) {
        $message = '<div class="message error">Mã Captcha không chính xác!</div>';
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password_input = $_POST['password'];

        $sql = "SELECT ma_nguoi_dung, ho_ten, mat_khau, vai_tro FROM nguoi_dung WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if ($password_input === $user['mat_khau']) {
                $_SESSION['user_id'] = $user['ma_nguoi_dung'];
                $_SESSION['full_name'] = $user['ho_ten'];
                $_SESSION['role'] = $user['vai_tro'];
                header("Location: ../index.php");
                exit();
            } else {
                $message = '<div class="message error">Sai email hoặc mật khẩu!</div>';
            }
        } else {
            $message = '<div class="message error">Sai email hoặc mật khẩu!</div>';
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Lẩu Hương Vị</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body class="auth-body">
    <div class="auth-container">  
        <section class="auth-form-section">
            <h1>ĐĂNG NHẬP</h1> 
            <?php echo $message; ?>
            <form method="POST" action="login.php">
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
                <a href="#" class="auth-link">Quên mật khẩu của bạn?</a>
                <button type="submit" class="auth-button">ĐĂNG NHẬP</button>
            </form>
        </section>
        <aside class="auth-sidebar">
            <div class="logo">
                <img src="../images/logo.png" alt="Royal TheDreamers Restaurant">
            </div>
            <h2>Bạn chưa có tài khoản tại Lẩu Hương Vị</h2>
            <p>Đừng lo, tạo mới một tài khoản và bắt đầu trải nghiệm của bạn với nhà hàng chúng tôi</p>
            <a href="register.php" class="cta-button-outline">Đăng ký >></a>
        </aside>
    </div>
</body>
</html>
