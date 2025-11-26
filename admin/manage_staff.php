<?php
include 'admin_auth.php';
check_admin_role(); 
include '../config/db.php';

$message = '';
$edit_mode = false;
$edit_user = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_user'])) {
    
    $ho_ten = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $vai_tro = mysqli_real_escape_string($conn, $_POST['vai_tro']);
    $password = $_POST['password'];
    $ma_nguoi_dung_edit = (int)$_POST['ma_nguoi_dung_edit'];
    
    if ($vai_tro != 'nhanvien' && $vai_tro != 'admin') {
        $vai_tro = 'nhanvien';
    }
    
    if ($ma_nguoi_dung_edit > 0) {
        $sql = "UPDATE nguoi_dung SET ho_ten = ?, email = ?, vai_tro = ? WHERE ma_nguoi_dung = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $ho_ten, $email, $vai_tro, $ma_nguoi_dung_edit);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
    } else {
        if (empty($password)) {
             $message = '<div class="message error">Mật khẩu là bắt buộc!</div>';
        } else {
            $sql_check = "SELECT ma_nguoi_dung FROM nguoi_dung WHERE email = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();
            
            if ($stmt_check->num_rows > 0) {
                $message = '<div class="message error">Email đã tồn tại!</div>';
            } else {
                // KHÔNG MÃ HÓA MẬT KHẨU (Lưu text rõ)
                $sql = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, vai_tro) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $ho_ten, $email, $password, $vai_tro);
                if ($stmt->execute()) {
                    $message = '<div class="message success">Thêm nhân sự thành công!</div>';
                } else {
                    $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
                }
            }
            $stmt_check->close();
        }
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    if ($delete_id == $_SESSION['admin_id']) {
        $message = '<div class="message error">Không thể tự xóa mình!</div>';
    } elseif ($delete_id > 0) {
        $sql = "DELETE FROM nguoi_dung WHERE ma_nguoi_dung = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Xóa thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($edit_id > 0) {
        $sql = "SELECT ma_nguoi_dung, ho_ten, email, vai_tro FROM nguoi_dung WHERE ma_nguoi_dung = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $edit_mode = true;
            $edit_user = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

$result_users = $conn->query("SELECT ma_nguoi_dung, ho_ten, email, vai_tro, ngay_tao FROM nguoi_dung 
                             WHERE vai_tro IN ('admin', 'nhanvien') 
                             ORDER BY ma_nguoi_dung DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhân Sự</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <main class="main-content">
            <h1>Quản Lý Nhân Sự</h1>
            <?php echo $message; ?>
            <div class="content-box">
                <h2><?php echo $edit_mode ? 'Sửa Tài Khoản' : 'Thêm Tài Khoản Mới'; ?></h2>
                <form method="POST" action="manage_staff.php">
                    <input type="hidden" name="ma_nguoi_dung_edit" value="<?php echo $edit_mode ? $edit_user['ma_nguoi_dung'] : '0'; ?>">
                    <div class="form-container">
                        <div class="form-group">
                            <label>Họ và Tên</label>
                            <input type="text" name="ho_ten" class="form-input" value="<?php echo $edit_mode ? htmlspecialchars($edit_user['ho_ten']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-input" value="<?php echo $edit_mode ? htmlspecialchars($edit_user['email']) : ''; ?>" required>
                        </div>
                         <div class="form-group">
                            <label>Vai Trò</label>
                            <select name="vai_tro" class="form-select">
                                <option value="nhanvien" <?php echo ($edit_mode && $edit_user['vai_tro'] == 'nhanvien') ? 'selected' : ''; ?>>Nhân viên</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mật Khẩu</label>
                            <input type="password" name="password" class="form-input" placeholder="<?php echo $edit_mode ? 'Để trống nếu không đổi' : 'Bắt buộc khi thêm mới'; ?>" <?php echo $edit_mode ? '' : 'required'; ?>>
                        </div>
                    </div>
                    <br>
                    <button type="submit" name="save_user" class="form-button"><?php echo $edit_mode ? 'Cập Nhật' : 'Thêm Mới'; ?></button>
                    <?php if ($edit_mode): ?>
                        <a href="manage_staff.php" style="margin-left: 10px;">Hủy</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="content-box">
                <h2>Danh Sách Nhân Sự</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>Vai Trò</th>
                            <th>Ngày Tạo</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_users->num_rows > 0): ?>
                            <?php while($row = $result_users->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_nguoi_dung']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo $row['vai_tro'] == 'admin' ? '<strong>Admin</strong>' : 'Nhân viên'; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['ngay_tao'])); ?></td>
                                    <td class="action-links">
                                        <a href="manage_staff.php?edit=<?php echo $row['ma_nguoi_dung']; ?>" class="edit-link">Sửa</a>
                                        <?php if ($row['ma_nguoi_dung'] != $_SESSION['admin_id']): ?>
                                            <a href="manage_staff.php?delete=<?php echo $row['ma_nguoi_dung']; ?>" class="delete-link" onclick="return confirm('Xóa tài khoản này?');">Xóa</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center;">Chưa có nhân sự nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>