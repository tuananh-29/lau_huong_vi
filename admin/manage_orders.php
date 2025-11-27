<?php
include 'admin_auth.php';
include '../config/db.php';

$message = '';

if (isset($_GET['update']) && isset($_GET['status'])) {
    $update_id = (int)$_GET['update'];
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);
    
    if ($update_id > 0 && in_array($new_status, ['dang_xu_ly', 'da_giao', 'da_huy'])) {
        
        $sql = "UPDATE don_hang SET trang_thai = ? WHERE ma_don_hang = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $update_id);
        
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật trạng thái đơn hàng thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

$result_orders = $conn->query("SELECT * FROM don_hang 
                                ORDER BY ngay_dat DESC, 
                                FIELD(trang_thai, 'cho_xac_nhan') DESC");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Hóa Đơn</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Thống Kê Hóa Đơn</h1>
            
            <?php echo $message; ?>

            <div class="content-box">
                <h2>Danh Sách Đơn Hàng</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Người Nhận</th>
                            <th>SĐT</th>
                            <th>Địa Chỉ</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_orders->num_rows > 0): ?>
                            <?php while($row = $result_orders->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_don_hang']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ten_nguoi_nhan']); ?></td>
                                    <td><?php echo htmlspecialchars($row['sdt_nguoi_nhan']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dia_chi_giao_hang']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['ngay_dat'])); ?></td>
                                    <td><?php echo number_format($row['tong_tien']); ?> VNĐ</td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['trang_thai']); ?></strong>
                                    </td>
                                    <td class="action-links">
                                        <?php if ($row['trang_thai'] == 'cho_xac_nhan'): ?>
                                            <a href="manage_orders.php?update=<?php echo $row['ma_don_hang']; ?>&status=dang_xu_ly" class="edit-link" style="color: green;">Xác nhận</a>
                                            <a href="manage_orders.php?update=<?php echo $row['ma_don_hang']; ?>&status=da_huy" class="delete-link" style="color: orange;">Hủy</a>
                                        <?php elseif ($row['trang_thai'] == 'dang_xu_ly'): ?>
                                             <a href="manage_orders.php?update=<?php echo $row['ma_don_hang']; ?>&status=da_giao" class="edit-link" style="color: blue;">Đã giao</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">Chưa có đơn hàng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>