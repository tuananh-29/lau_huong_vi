<?php
include 'admin_auth.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Xuất Kho</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <h1>Quản Lý Xuất Kho</h1>
            
            <div class="content-box">
                <h2>Chức năng phức tạp</h2>
                <p>Quản lý xuất kho (hay còn gọi là "Định lượng món ăn") là một chức năng rất phức tạp.</p>
                <p>Nó đòi hỏi phải có một bảng CSDL mới tên là `dinh_luong_mon_an` (ví dụ: 1 Lẩu Thái = 0.5kg thịt bò + 1kg rau...).</p>
                <p>Khi admin xác nhận 1 đơn hàng (`manage_orders.php`) là "Đã giao", hệ thống phải tự động đọc bảng `dinh_luong_mon_an` và trừ số lượng tương ứng trong bảng `nguyen_lieu`.</p>
                <p>Trang này thường dùng để xem lịch sử xuất kho (hủy hàng, hoặc xuất tự động khi bán).</p>
            </div>

        </main>
    </div>

</body>
</html>