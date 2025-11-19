<?php
include 'admin_auth.php';
include '../config/db.php';

$message = '';

if (isset($_GET['delete'])) {
    check_admin_role();
    $delete_id = (int)$_GET['delete'];
    
    if ($delete_id > 0) {
        $sql = "DELETE FROM nguoi_dung WHERE ma_nguoi_dung = ? AND vai_tro = 'khachhang'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Xóa khách hàng thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

$result_customers = $conn->query("SELECT ma_nguoi_dung, ho_ten, email, so_dien_thoai, ngay_tao FROM nguoi_dung 
                                  WHERE vai_tro = 'khachhang' 
                                  ORDER BY ma_nguoi_dung DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <main class="main-content">
            <h1>Quản Lý Khách Hàng</h1>
            <?php echo $message; ?>
            <div class="content-box">
                <h2>Danh Sách Khách Hàng</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>Ngày Tham Gia</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_customers->num_rows > 0): ?>
                            <?php while($row = $result_customers->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_nguoi_dung']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['so_dien_thoai']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['ngay_tao'])); ?></td>
                                    <td class="action-links">
                                        <a href="manage_customers.php?delete=<?php echo $row['ma_nguoi_dung']; ?>" class="delete-link" onclick="return confirm('Xóa khách hàng này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center;">Chưa có khách hàng nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>