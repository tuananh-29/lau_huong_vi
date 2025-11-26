<?php
include 'admin_auth.php';
include '../config/db.php';

$message = '';

if (isset($_GET['update']) && isset($_GET['status'])) {
    $update_id = (int)$_GET['update'];
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);
    
    if ($update_id > 0 && in_array($new_status, ['da_xac_nhan', 'da_huy', 'da_den'])) {
        $sql = "UPDATE dat_ban SET trang_thai = ? WHERE ma_dat_ban = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $update_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật trạng thái thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    if ($delete_id > 0) {
        $sql = "DELETE FROM dat_ban WHERE ma_dat_ban = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Xóa lịch đặt bàn thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

$result_bookings = $conn->query("SELECT * FROM dat_ban 
                                 ORDER BY ngay_den DESC, 
                                 FIELD(trang_thai, 'cho_xac_nhan') DESC, 
                                 gio_den ASC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đặt Bàn</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <main class="main-content">
            <h1>Quản Lý Đặt Bàn</h1>
            <?php echo $message; ?>
            <div class="content-box">
                <h2>Danh Sách Đặt Bàn</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách Hàng</th>
                            <th>SĐT</th>
                            <th>Ngày/Giờ Đến</th>
                            <th>Số Khách</th>
                            <th>Ghi Chú</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_bookings->num_rows > 0): ?>
                            <?php while($row = $result_bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_dat_ban']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ten_nguoi_dat']); ?></td>
                                    <td><?php echo htmlspecialchars($row['sdt_nguoi_dat']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['ngay_den'])); ?> lúc <?php echo date('H:i', strtotime($row['gio_den'])); ?></td>
                                    <td><?php echo $row['so_luong_khach']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ghi_chu']); ?></td>
                                    <td>
                                        <?php 
                                            if($row['trang_thai'] == 'cho_xac_nhan') echo '<span style="color:orange; font-weight:bold;">Chờ xác nhận</span>';
                                            elseif($row['trang_thai'] == 'da_xac_nhan') echo '<span style="color:green; font-weight:bold;">Đã xác nhận</span>';
                                            elseif($row['trang_thai'] == 'da_huy') echo '<span style="color:red; font-weight:bold;">Đã hủy</span>';
                                            else echo $row['trang_thai'];
                                        ?>
                                    </td>
                                    <td class="action-links">
                                        <?php if ($row['trang_thai'] == 'cho_xac_nhan'): ?>
                                            <a href="manage_reservations.php?update=<?php echo $row['ma_dat_ban']; ?>&status=da_xac_nhan" class="edit-link" style="color: green;">Duyệt</a>
                                            <a href="manage_reservations.php?update=<?php echo $row['ma_dat_ban']; ?>&status=da_huy" class="delete-link" style="color: orange;">Hủy</a>
                                        <?php endif; ?>
                                        <a href="manage_reservations.php?delete=<?php echo $row['ma_dat_ban']; ?>" class="delete-link" onclick="return confirm('Xóa lịch này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="text-align: center;">Chưa có lịch đặt bàn nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>