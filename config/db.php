<?php
$servername = "localhost"; 
$username = "root";      
$password = "";            
$dbname = "lau_huong_vi_db";  
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, 'UTF8');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>