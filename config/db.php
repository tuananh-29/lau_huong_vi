<?php

$servername = "localhost"; // Hầu hết là "localhost"
$username = "root";        // Tên đăng nhập MySQL (ví dụ: root)
$password = "";            // Mật khẩu MySQL (trên XAMPP thường là bỏ trống)
$dbname = "lau_huong_vi_db";   // Tên database mà bạn đã tạo (từ bài trước)

// -----------------------------------------------------------------

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Đặt bộ mã utf8 để hiển thị tiếng Việt
mysqli_set_charset($conn, 'UTF8');

// Kiểm tra kết nối
if ($conn->connect_error) {
    // Nếu thất bại, hiển thị lỗi và dừng chương trình
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Nếu tệp này được nhúng (include) thành công,
// biến $conn sẽ khả dụng để sử dụng trong các tệp khác.
?>