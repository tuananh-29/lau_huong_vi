<?php
include 'admin_auth.php';
include '../config/db.php';

$message = '';

$result_stock_in = $conn->query(
    "SELECT pnk.*, nd.ho_ten 
     FROM phieu_nhap_kho AS pnk
     JOIN nguoi_dung AS nd ON pnk.ma_nhan_vien = nd.ma_nguoi_dung
     ORDER BY pnk.ngay_nhap DESC"
);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhập Kho</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Quản Lý Nhập Kho</h1>
            
            <?php echo $message; ?>

            <div class="content-box">
                <a href="#" class="form-button" style="text-decoration: none; display: inline-block;">+ Tạo Phiếu Nhập Mới</a>
            </div>

            <div class="content-box">
                <h2>Danh Sách Phiếu Nhập Kho</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Phiếu</th>
                            <th>Người Lập Phiếu</th>
                            <th>Ngày Nhập</th>
                            <th>Tổng Tiền</th>
                            <th>Ghi Chú</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_stock_in->num_rows > 0): ?>
                            <?php while($row = $result_stock_in->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_phieu_nhap']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['ngay_nhap'])); ?></td>
                                    <td><?php echo number_format($row['tong_tien']); ?> VNĐ</td>
                                    <td><?php echo htmlspecialchars($row['ghi_chu']); ?></td>
                                    <td class="action-links">
                                        <a href="#" class="edit-link">Xem Chi Tiết</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Chưa có phiếu nhập kho nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>