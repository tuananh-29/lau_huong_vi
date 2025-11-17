<?php
include 'admin_auth.php';
include '../config/db.php';

$message = '';
$edit_mode = false;
$edit_product = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_product'])) {
    $ten_mon_an = mysqli_real_escape_string($conn, $_POST['ten_mon_an']);
    $ma_danh_muc = (int)$_POST['ma_danh_muc'];
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $gia = (int)$_POST['gia'];
    $trang_thai = mysqli_real_escape_string($conn, $_POST['trang_thai']);
    $anh = mysqli_real_escape_string($conn, $_POST['anh']);
    $ma_mon_an_edit = (int)$_POST['ma_mon_an_edit'];
    
    if ($ma_mon_an_edit > 0) {
        $sql = "UPDATE mon_an SET ten_mon_an = ?, ma_danh_muc = ?, mo_ta = ?, gia = ?, anh = ?, trang_thai = ? WHERE ma_mon_an = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisissi", $ten_mon_an, $ma_danh_muc, $mo_ta, $gia, $anh, $trang_thai, $ma_mon_an_edit);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật món ăn thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
    } else {
        $sql = "INSERT INTO mon_an (ten_mon_an, ma_danh_muc, mo_ta, gia, anh, trang_thai) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisiss", $ten_mon_an, $ma_danh_muc, $mo_ta, $gia, $anh, $trang_thai);
        if ($stmt->execute()) {
            $message = '<div class="message success">Thêm món ăn mới thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    if ($delete_id > 0) {
        $sql = "DELETE FROM mon_an WHERE ma_mon_an = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Xóa món ăn thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($edit_id > 0) {
        $sql = "SELECT * FROM mon_an WHERE ma_mon_an = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $edit_mode = true;
            $edit_product = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

$result_products = $conn->query("SELECT * FROM mon_an ORDER BY ma_mon_an DESC");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thực Đơn</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Quản Lý Thực Đơn</h1>
            
            <?php echo $message; ?>

            <div class="content-box">
                <h2><?php echo $edit_mode ? 'Sửa Món Ăn' : 'Thêm Món Ăn Mới'; ?></h2>
                <form method="POST" action="manage_menu.php">
                    <input type="hidden" name="ma_mon_an_edit" value="<?php echo $edit_mode ? $edit_product['ma_mon_an'] : '0'; ?>">
                    
                    <div class="form-container">
                        <div class="form-group" style="grid-column: 1 / span 2;">
                            <label for="ten_mon_an">Tên Món Ăn</label>
                            <input type="text" id="ten_mon_an" name="ten_mon_an" class="form-input" 
                                   value="<?php echo $edit_mode ? htmlspecialchars($edit_product['ten_mon_an']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="ma_danh_muc">Danh Mục</label>
                            <input type="number" id="ma_danh_muc" name="ma_danh_muc" class="form-input" 
                                   value="<?php echo $edit_mode ? $edit_product['ma_danh_muc'] : '1'; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="gia">Giá (VNĐ)</label>
                            <input type="number" id="gia" name="gia" class="form-input" 
                                   value="<?php echo $edit_mode ? $edit_product['gia'] : '0'; ?>" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / span 2;">
                            <label for="anh">Link Hình Ảnh</label>
                            <input type="text" id="anh" name="anh" class="form-input" 
                                   value="<?php echo $edit_mode ? htmlspecialchars($edit_product['anh']) : ''; ?>">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="mo_ta">Mô Tả</label>
                            <textarea id="mo_ta" name="mo_ta" class="form-textarea"><?php echo $edit_mode ? htmlspecialchars($edit_product['mo_ta']) : ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="trang_thai">Trạng Thái</label>
                            <select id="trang_thai" name="trang_thai" class="form-select">
                                <option value="con_hang" <?php echo ($edit_mode && $edit_product['trang_thai'] == 'con_hang') ? 'selected' : ''; ?>>Còn hàng</option>
                                <option value="het_hang" <?php echo ($edit_mode && $edit_product['trang_thai'] == 'het_hang') ? 'selected' : ''; ?>>Hết hàng</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <button type="submit" name="save_product" class="form-button">
                        <?php echo $edit_mode ? 'Cập Nhật Món' : 'Thêm Món Mới'; ?>
                    </button>
                    <?php if ($edit_mode): ?>
                        <a href="manage_menu.php" style="margin-left: 10px; text-decoration: none;">Hủy Sửa</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="content-box">
                <h2>Danh Sách Món Ăn</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên Món Ăn</th>
                            <th>Giá</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_products->num_rows > 0): ?>
                            <?php while($row = $result_products->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_mon_an']; ?></td>
                                    <td><img src="<?php echo htmlspecialchars($row['anh']); ?>" alt="<?php echo htmlspecialchars($row['ten_mon_an']); ?>"></td>
                                    <td><?php echo htmlspecialchars($row['ten_mon_an']); ?></td>
                                    <td><?php echo number_format($row['gia']); ?> VNĐ</td>
                                    <td><?php echo $row['trang_thai'] == 'con_hang' ? 'Còn hàng' : 'Hết hàng'; ?></td>
                                    <td class="action-links">
                                        <a href="manage_menu.php?edit=<?php echo $row['ma_mon_an']; ?>" class="edit-link">Sửa</a>
                                        <a href="manage_menu.php?delete=<?php echo $row['ma_mon_an']; ?>" class="delete-link" onclick="return confirm('Bạn có chắc chắn muốn xóa món này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Chưa có món ăn nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>
