<?php
include 'admin_auth.php';
include '../config/db.php';

$today = date('Y-m-d');
$start_of_month = date('Y-m-01');

$result_today = $conn->query("SELECT SUM(tong_tien) as total FROM don_hang WHERE trang_thai = 'da_giao' AND DATE(ngay_dat) = '$today'");
$revenue_today = $result_today->fetch_assoc()['total'];

$result_month = $conn->query("SELECT SUM(tong_tien) as total FROM don_hang WHERE trang_thai = 'da_giao' AND DATE(ngay_dat) >= '$start_of_month'");
$revenue_month = $result_month->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Doanh Thu</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Báo Cáo Doanh Thu</h1>
            
            <div class="content-box">
                <form method="GET" action="report_revenue.php">
                    <div class="form-container" style="gap: 10px; align-items: flex-end;">
                        <div class="form-group">
                            <label>Từ ngày:</label>
                            <input type="date" name="from_date" class="form-input" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : $start_of_month; ?>">
                        </div>
                         <div class="form-group">
                            <label>Đến ngày:</label>
                            <input type="date" name="to_date" class="form-input" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : $today; ?>">
                        </div>
                        <div class="form-group">
                             <button type="submit" class="form-button">Xem Báo Cáo</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="dashboard-cards">
                 <div class="card">
                    <h3>Doanh Thu Hôm Nay</h3>
                    <div class="value"><?php echo number_format($revenue_today, 0, ',', '.'); ?> VNĐ</div>
                </div>
                 <div class="card">
                    <h3>Doanh Thu Tháng Này</h3>
                    <div class="value"><?php echo number_format($revenue_month, 0, ',', '.'); ?> VNĐ</div>
                </div>
            </div>

            <div class="content-box" style="margin-top: 20px;">
                <h2>Biểu Đồ Doanh Thu</h2>
        </main>
    </div>

</body>
</html>