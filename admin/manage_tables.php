<?php
include 'admin_auth.php';
include '../config/db.php';

$message = '';
$edit_mode = false;
$edit_table = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_table'])) {
    
    $ten_ban = mysqli_real_escape_string($conn, $_POST['ten_ban']);
    $so_ghe = (int)$_POST['so_ghe'];
    $trang_thai = mysqli_real_escape_string($conn, $_POST['trang_thai']);
    $ma_ban_edit = (int)$_POST['ma_ban_edit'];
    
    if ($ma_ban_edit > 0) {
        $sql = "UPDATE ban_an SET ten_ban = ?, so_ghe = ?, trang_thai = ? WHERE ma_ban = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisi", $ten_ban, $so_ghe, $trang_thai, $ma_ban_edit);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật bàn thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
    } else {
        $sql = "INSERT INTO ban_an (ten_ban, so_ghe, trang_thai) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $ten_ban, $so_ghe, $trang_thai);
        if ($stmt->execute()) {
            $message = '<div class="message success">Thêm bàn mới thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    if ($delete_id > 0) {
        $sql = "DELETE FROM ban_an WHERE ma_ban = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Xóa bàn thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($edit_id > 0) {
        $sql = "SELECT * FROM ban_an WHERE ma_ban = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $edit_mode = true;
            $edit_table = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

$result_tables = $conn->query("SELECT * FROM ban_an ORDER BY ten_ban ASC");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Bàn</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Quản Lý Bàn</h1>
            
            <?php echo $message; ?>

            <div class="content-box">
                <h2><?php echo $edit_mode ? 'Sửa Bàn' : 'Thêm Bàn Mới'; ?></h2>
                <form method="POST" action="manage_tables.php">
                    <input type="hidden" name="ma_ban_edit" value="<?php echo $edit_mode ? $edit_table['ma_ban'] : '0'; ?>">
                    
                    <div class="form-container">
                        <div class="form-group">
                            <label for="ten_ban">Tên Bàn (Ví dụ: Bàn 1, Phòng VIP 2)</label>
                            <input type="text" id="ten_ban" name="ten_ban" class="form-input" 
                                   value="<?php echo $edit_mode ? htmlspecialchars($edit_table['ten_ban']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="so_ghe">Số Ghế</label>
                            <input type="number" id="so_ghe" name="so_ghe" class="form-input" 
                                   value="<?php echo $edit_mode ? $edit_table['so_ghe'] : '4'; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="trang_thai">Trạng Thái</label>
                            <select id="trang_thai" name="trang_thai" class="form-select">
                                <option value="trong" <?php echo ($edit_mode && $edit_table['trang_thai'] == 'trong') ? 'selected' : ''; ?>>Trống</option>
                                <option value="dang_co_khach" <?php echo ($edit_mode && $edit_table['trang_thai'] == 'dang_co_khach') ? 'selected' : ''; ?>>Đang có khách</option>
                                <option value="da_dat" <?php echo ($edit_mode && $edit_table['trang_thai'] == 'da_dat') ? 'selected' : ''; ?>>Đã đặt</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <button type="submit" name="save_table" class="form-button">
                        <?php echo $edit_mode ? 'Cập Nhật Bàn' : 'Thêm Bàn'; ?>
                    </button>
                    <?php if ($edit_mode): ?>
                        <a href="manage_tables.php" style="margin-left: 10px; text-decoration: none;">Hủy Sửa</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="content-box">
                <h2>Danh Sách Bàn</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Bàn</th>
                            <th>Số Ghế</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_tables->num_rows > 0): ?>
                            <?php while($row = $result_tables->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_ban']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ten_ban']); ?></td>
                                    <td><?php echo $row['so_ghe']; ?></td>
                                    <td><?php echo htmlspecialchars($row['trang_thai']); ?></td>
                                    <td class="action-links">
                                        <a href="manage_tables.php?edit=<?php echo $row['ma_ban']; ?>" class="edit-link">Sửa</a>
                                        <a href="manage_tables.php?delete=<?php echo $row['ma_ban']; ?>" class="delete-link" onclick="return confirm('Bạn có chắc chắn muốn xóa bàn này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Chưa có bàn nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>