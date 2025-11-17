<?php
include 'admin_auth.php'; 
include '../config/db.php'; 
$result_revenue = $conn->query("SELECT SUM(tong_tien) as total_revenue FROM don_hang WHERE trang_thai = 'da_giao'");
$total_revenue = $result_revenue->fetch_assoc()['total_revenue'];

$result_new_orders = $conn->query("SELECT COUNT(*) as new_orders FROM don_hang WHERE trang_thai = 'cho_xac_nhan'");
$new_orders = $result_new_orders->fetch_assoc()['new_orders'];

$result_new_bookings = $conn->query("SELECT COUNT(*) as new_bookings FROM dat_ban WHERE trang_thai = 'cho_xac_nhan'");
$new_bookings = $result_new_bookings->fetch_assoc()['new_bookings'];

$result_customers = $conn->query("SELECT COUNT(*) as total_customers FROM nguoi_dung WHERE vai_tro = 'khachhang'");
$total_customers = $result_customers->fetch_assoc()['total_customers'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Bảng Điều Khiển</h1>

            <div class="dashboard-cards">
                <div class="card">
                    <h3>Tổng Doanh Thu (Đã giao)</h3>
                    <div class="value"><?php echo number_format($total_revenue, 0, ',', '.'); ?> VNĐ</div>
                </div>
                <div class="card">
                    <h3>Đơn Hàng Mới</h3>
                    <div class="value"><?php echo $new_orders; ?></div>
                </div>
                <div class="card">
                    <h3>Đặt Bàn Mới</h3>
                    <div class="value"><?php echo $new_bookings; ?></div>
                </div>
                <div class="card">
                    <h3>Tổng Khách Hàng</h3>
                    <div class="value"><?php echo $total_customers; ?></div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>