<?php
// Bắt đầu session
session_start();

// --- BẢO VỆ TRANG ---
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa, chuyển hướng họ đến trang đăng nhập
    header("Location: login.php");
    exit(); // Dừng script
}

// Bao gồm tệp kết nối
include 'config/db.php';

$message = ''; // Biến lưu thông báo

// Kiểm tra nếu form đã được gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. KIỂM TRA CAPTCHA TRƯỚC ---
    if (!isset($_POST['captcha']) || strtolower($_POST['captcha']) != strtolower($_SESSION['captcha_code'])) {
        $message = '<div class="message error">Mã Captcha không chính xác!</div>';
    } else {
        // --- 2. CAPTCHA ĐÚNG -> TIẾP TỤC XỬ LÝ ĐẶT BÀN ---
        
        // (Toàn bộ code lấy $customer_name, $user_id, $sql... của bạn nằm ở đây)
        $customer_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        // ...
        
    } // Đóng khối 'else' của Captcha
    
    // 1. Lấy dữ liệu từ form (Phần này đã đúng)
    $customer_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $res_date = mysqli_real_escape_string($conn, $_POST['res_date']);
    $res_time = mysqli_real_escape_string($conn, $_POST['res_time']);
    $guests = (int)$_POST['guests'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $status = 'cho_xac_nhan'; // Trạng thái mặc định (Khớp SQL)

    // 2. Lấy user_id (đã đăng nhập)
    $user_id = $_SESSION['user_id'];

    // 3. SỬA 2: Sử dụng Prepared Statements với đúng tên bảng/cột
    $sql = "INSERT INTO dat_ban (ma_nguoi_dung, te_nguoi_dat, sdt_nguoi_dat, ngay_den, gio_den, so_luong_khach, ghi_chu, trang_thai) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // "issssis" = (i)nt, (s)tring, (s)tring, (s)tring, (s)tring, (i)nt, (s)tring, (s)tring
    // Các biến PHP này đã đúng thứ tự, không cần sửa
    $stmt->bind_param("isssisss", $user_id, $customer_name, $customer_phone, $res_date, $res_time, $guests, $notes, $status);

    // 4. Thực thi và báo kết quả
    if ($stmt->execute()) {
        $message = '<div class="message success">Đặt bàn thành công! Chúng tôi sẽ liên hệ với bạn để xác nhận.</div>';
    } else {
        $message = '<div class="message error">Đã có lỗi xảy ra. Vui lòng thử lại.</div>';
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Bàn - Lẩu Hương Vị</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php 
        // SỬA 1: Nhúng phần đầu trang (header) vào đây
        include 'php/header.php'; 
    ?>

    <!-- ==================== MAIN ==================== -->
    <main>
        <section class="auth-page reservation-page">
            <div class="container">
                <h2>Đặt Bàn</h2>
                <p style="text-align: center; max-width: 600px; margin: 0 auto 30px auto;">Vui lòng điền thông tin bên dưới để đặt bàn. Chỉ dành cho khách hàng đã đăng nhập.</p>
                
                <!-- Hiển thị thông báo (nếu có) -->
                <?php echo $message; ?>

                <!-- Form Đặt Bàn (HTML của bạn đã đúng, không cần sửa) -->
                <div class="auth-form-container">
                    <form method="POST" action="reservation.php">
                        
                        <!-- Lấy tên từ session -->
                        <?php
                            // Lấy 'full_name' từ session (được gán lúc login)
                            $default_name = htmlspecialchars($_SESSION['full_name']);
                        ?>

                        <div class="form-group">
                            <label for="full_name">Họ và Tên (Người Đặt)</label>
                            <input type="text" id="full_name" name="full_name" class="form-input" value="<?php echo $default_name; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone_number">Số Điện Thoại Liên Hệ</label>
                            <input type="tel" id="phone_number" name="phone_number" class="form-input" placeholder="Ví dụ: 0901234567" required>
                        </div>
                        
                        <div class="form-grid"> <!-- Bố cục 2 cột -->
                            <div class="form-group">
                                <label for="res_date">Ngày Đặt</label>
                                <input type="date" id="res_date" name="res_date" class="form-input" required>
                            </div>
                             <div class="form-group">
                                <label for="res_time">Giờ Đặt</label>
                                <input type="time" id="res_time" name="res_time" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="guests">Số Lượng Khách</label>
                            <input type="number" id="guests" name="guests" class="form-input" value="1" min="1" max="50" required>
                        </div>

                        <div class="form-group">
                            <label for="notes">Ghi Chú (Tùy chọn)</label>
                            <textarea id="notes" name="notes" class="form-textarea" placeholder="Ví dụ: Cho tôi bàn gần cửa sổ..."></textarea>
                        </div>

                        <button type="submit" class="cta-button form-button">Xác Nhận Đặt Bàn</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php 
        // SỬA 1: Nhúng phần chân trang (footer) vào đây
        include 'php/footer.php'; 
    ?>

    <script src="js/script.js"></script>
</body>
</html>