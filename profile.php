<?php
session_start();
// SỬA 1: Đường dẫn đúng phải là '../config/db.php'
include 'config/db.php'; 

// Phải đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: php/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message_info = '';
$message_pass = '';

// --- XỬ LÝ CẬP NHẬT THÔNG TIN ---
if (isset($_POST['update_info'])) {
    $ho_ten = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $so_dien_thoai = mysqli_real_escape_string($conn, $_POST['so_dien_thoai']);
    
    $sql = "UPDATE nguoi_dung SET ho_ten = ?, so_dien_thoai = ? WHERE ma_nguoi_dung = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $ho_ten, $so_dien_thoai, $user_id);
    
    if ($stmt->execute()) {
        $message_info = '<div class="message success">Cập nhật thông tin thành công!</div>';
        $_SESSION['full_name'] = $ho_ten; // Cập nhật lại tên trên session
    } else {
        $message_info = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

// --- XỬ LÝ ĐỔI MẬT KHẨU ---
if (isset($_POST['update_password'])) {
    $pass_old = $_POST['pass_old'];
    $pass_new = $_POST['pass_new'];
    $pass_confirm = $_POST['pass_confirm'];

    // Lấy mk cũ
    $sql_check = "SELECT mat_khau FROM nguoi_dung WHERE ma_nguoi_dung = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $user = $result_check->fetch_assoc();

    if (password_verify($pass_old, $user['mat_khau'])) {
        // Mật khẩu cũ đúng
        if ($pass_new == $pass_confirm) {
            // Mật khẩu mới trùng khớp
            $hashed_password = password_hash($pass_new, PASSWORD_DEFAULT);
            $sql_pass = "UPDATE nguoi_dung SET mat_khau = ? WHERE ma_nguoi_dung = ?";
            $stmt_pass = $conn->prepare($sql_pass);
            $stmt_pass->bind_param("si", $hashed_password, $user_id);
            
            if ($stmt_pass->execute()) {
                $message_pass = '<div class="message success">Đổi mật khẩu thành công!</div>';
            } else {
                $message_pass = '<div class="message error">Lỗi khi đổi mật khẩu.</div>';
            }
            $stmt_pass->close();
        } else {
            $message_pass = '<div class="message error">Mật khẩu mới không trùng khớp!</div>';
        }
    } else {
        $message_pass = '<div class="message error">Mật khẩu cũ không chính xác!</div>';
    }
    $stmt_check->close();
}

// Lấy thông tin user hiện tại để điền vào form
$sql_user = "SELECT ho_ten, email, so_dien_thoai FROM nguoi_dung WHERE ma_nguoi_dung = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$current_user = $result_user->fetch_assoc();
$stmt_user->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Cá Nhân - Lẩu Hương Vị</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'php/header.php'; ?>
    <main>
        <section class="auth-page">
            <div class="profile-page-container">

                <div class="profile-column">
                    <h2>Thông Tin Cá Nhân</h2>
                    <?php echo $message_info; ?>
                    <div class="auth-form-container">
                        <form method="POST" action="profile.php">
                            <div class="form-group">
                                <label for="email">Email (Không thể thay đổi)</label>
                                <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($current_user['email']); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="ho_ten">Họ và Tên</label>
                                <input type="text" id="ho_ten" name="ho_ten" class="form-input" value="<?php echo htmlspecialchars($current_user['ho_ten']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="so_dien_thoai">Số Điện Thoại</label>
                                <input type="tel" id="so_dien_thoai" name="so_dien_thoai" class="form-input" value="<?php echo htmlspecialchars($current_user['so_dien_thoai']); ?>">
                            </div>
                            <button type="submit" name="update_info" class="cta-button form-button">Lưu Thay Đổi</button>
                        </form>
                    </div>
                </div>

                <div class="profile-column">
                    <h2>Đổi Mật Khẩu</h2>
                    <?php echo $message_pass; ?>
                    <div class="auth-form-container">
                        <form method="POST" action="profile.php">
                            <div class="form-group">
                                <label for="pass_old">Mật Khẩu Cũ</label>
                                <input type="password" id="pass_old" name="pass_old" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="pass_new">Mật Khẩu Mới</label>
                                <input type="password" id="pass_new" name="pass_new" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="pass_confirm">Xác Nhận Mật Khẩu Mới</label>
                                <input type="password" id="pass_confirm" name="pass_confirm" class="form-input" required>
                            </div>
                            <button type="submit" name="update_password" class="cta-button form-button">Đổi Mật Khẩu</button>
                        </form>
                    </div>
                </div>

            </div>
        </section>
    </main>
    <?php include 'php/footer.php'; ?>
</body>
</html>