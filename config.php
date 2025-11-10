<?php
/**
 * File: config.php
 * Thiết lập kết nối cơ sở dữ liệu MySQL (hoặc MariaDB) cho Laragon
 */

$servername = "localhost"; 
$username = "root";       
$password = "";           // Mật khẩu CSDL (thường là rỗng)
$dbname = "db_Tennis_club";  // TÊN CSDL ĐÃ ĐƯỢC CẬP NHẬT

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("❌ Kết nối CSDL thất bại: " . $conn->connect_error);
}

// Thiết lập mã hóa UTF-8
$conn->set_charset("utf8mb4"); 
?>