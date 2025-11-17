<?php
include 'admin_auth.php';
include '../config/db.php';

$message = '';
$edit_mode = false;
$edit_ingredient = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_ingredient'])) {
    
    $ten_nguyen_lieu = mysqli_real_escape_string($conn, $_POST['ten_nguyen_lieu']);
    $don_vi_tinh = mysqli_real_escape_string($conn, $_POST['don_vi_tinh']);
    $ma_nguyen_lieu_edit = (int)$_POST['ma_nguyen_lieu_edit'];
    
    if ($ma_nguyen_lieu_edit > 0) {
        $sql = "UPDATE nguyen_lieu SET ten_nguyen_lieu = ?, don_vi_tinh = ? WHERE ma_nguyen_lieu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $ten_nguyen_lieu, $don_vi_tinh, $ma_nguyen_lieu_edit);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật nguyên liệu thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
    } else {
        $sql = "INSERT INTO nguyen_lieu (ten_nguyen_lieu, don_vi_tinh, so_luong_ton_kho) VALUES (?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $ten_nguyen_lieu, $don_vi_tinh);
        if ($stmt->execute()) {
            $message = '<div class="message success">Thêm nguyên liệu mới thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    if ($delete_id > 0) {
        $sql = "DELETE FROM nguyen_lieu WHERE ma_nguyen_lieu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Xóa nguyên liệu thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: Không thể xóa nguyên liệu đã được sử dụng.</div>';
        }
        $stmt->close();
    }
}

if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($edit_id > 0) {
        $sql = "SELECT * FROM nguyen_lieu WHERE ma_nguyen_lieu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $edit_mode = true;
            $edit_ingredient = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

$result_ingredients = $conn->query("SELECT * FROM nguyen_lieu ORDER BY ten_nguyen_lieu ASC");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nguyên Liệu</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Quản Lý Nguyên Liệu</h1>
            
            <?php echo $message; ?>

            <div class="content-box">
                <h2><?php echo $edit_mode ? 'Sửa Nguyên Liệu' : 'Thêm Nguyên Liệu Mới'; ?></h2>
                <form method="POST" action="manage_ingredients.php">
                    <input type="hidden" name="ma_nguyen_lieu_edit" value="<?php echo $edit_mode ? $edit_ingredient['ma_nguyen_lieu'] : '0'; ?>">
                    
                    <div class="form-container">
                        <div class="form-group">
                            <label for="ten_nguyen_lieu">Tên Nguyên Liệu (Ví dụ: Thịt bò, Cải thảo)</label>
                            <input type="text" id="ten_nguyen_lieu" name="ten_nguyen_lieu" class="form-input" 
                                   value="<?php echo $edit_mode ? htmlspecialchars($edit_ingredient['ten_nguyen_lieu']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="don_vi_tinh">Đơn Vị Tính (kg, gram, lít, cái...)</label>
                            <input type="text" id="don_vi_tinh" name="don_vi_tinh" class="form-input" 
                                   value="<?php echo $edit_mode ? htmlspecialchars($edit_ingredient['don_vi_tinh']) : ''; ?>" required>
                        </div>
                    </div>
                    <br>
                    <button type="submit" name="save_ingredient" class="form-button">
                        <?php echo $edit_mode ? 'Cập Nhật' : 'Thêm Mới'; ?>
                    </button>
                    <?php if ($edit_mode): ?>
                        <a href="manage_ingredients.php" style="margin-left: 10px; text-decoration: none;">Hủy Sửa</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="content-box">
                <h2>Danh Sách Nguyên Liệu (Trong Kho)</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Nguyên Liệu</th>
                            <th>Đơn Vị Tính</th>
                            <th>Tồn Kho</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_ingredients->num_rows > 0): ?>
                            <?php while($row = $result_ingredients->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_nguyen_lieu']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ten_nguyen_lieu']); ?></td>
                                    <td><?php echo htmlspecialchars($row['don_vi_tinh']); ?></td>
                                    <td><strong><?php echo $row['so_luong_ton_kho']; ?></strong></td>
                                    <td class="action-links">
                                        <a href="manage_ingredients.php?edit=<?php echo $row['ma_nguyen_lieu']; ?>" class="edit-link">Sửa</a>
                                        <a href="manage_ingredients.php?delete=<?php echo $row['ma_nguyen_lieu']; ?>" class="delete-link" onclick="return confirm('Bạn có chắc chắn muốn xóa nguyên liệu này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Chưa có nguyên liệu nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>