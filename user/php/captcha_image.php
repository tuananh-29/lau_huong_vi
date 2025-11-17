<?php
session_start();

// Tạo chuỗi ngẫu nhiên 5 ký tự
$captcha_code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

// Lưu vào session
$_SESSION['captcha_code'] = $captcha_code;

// Tạo hình ảnh
header('Content-type: image/png');
$image = imagecreate(100, 30);
$background_color = imagecolorallocate($image, 255, 255, 255); // Nền trắng
$text_color = imagecolorallocate($image, 20, 20, 20); // Chữ đen

// Thêm nhiễu
for ($i = 0; $i < 50; $i++) {
    imagefilledellipse($image, mt_rand(0,100), mt_rand(0,30), 1, 1, imagecolorallocate($image, 200, 200, 200));
}

// Vẽ chữ
imagestring($image, 5, 20, 6, $captcha_code, $text_color);

// Xuất ảnh
imagepng($image);
imagedestroy($image);
?>