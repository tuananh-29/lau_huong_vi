<?php
include 'admin_auth.php';
include '../config/db.php';

$result_inventory = $conn->query("SELECT * FROM nguyen_lieu ORDER BY ten_nguyen_lieu ASC");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Kho</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Quản Lý Kho</h1>
            
            <div class="content-box">
                <h2>Báo Cáo Tồn Kho</h2>
                <p style="margin-bottom: 15px;">Để thêm/sửa nguyên liệu, vui lòng vào trang "Quản lý Nguyên Liệu".<br>Để cập nhật số lượng tồn kho, vui lòng tạo "Phiếu Nhập Kho".</p>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Nguyên Liệu</th>
                            <th>Đơn Vị Tính</th>
                            <th>Số Lượng Tồn Kho</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_inventory->num_rows > 0): ?>
                            <?php while($row = $result_inventory->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_nguyen_lieu']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ten_nguyen_lieu']); ?></td>
                                    <td><?php echo htmlspecialchars($row['don_vi_tinh']); ?></td>
                                    <td><strong><?php echo $row['so_luong_ton_kho']; ?></strong></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Chưa có nguyên liệu nào trong kho.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>