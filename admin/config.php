<?php
/**
 * File: config.php
 * Thiết lập kết nối cơ sở dữ liệu MySQL (hoặc MariaDB) cho Laragon
<?php
/**
 * File: admin/config.php
 * Kết nối DB cho khu vực admin, bật session và header bảo mật.
 */

// Bắt session admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security headers for admin pages (sent early - config is included before output)
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header("Permissions-Policy: geolocation=(), microphone=()");
// Minimal CSP - adjust if you load resources from other domains
header("Content-Security-Policy: default-src 'self' https: data:; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline' https:");

$servername = "localhost"; 
$username = "root";       
$password = "";           // Mật khẩu CSDL (thường là rỗng)
$dbname = "db_tennis_club";  // TÊN CSDL

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối - log thay vì hiển thị chi tiết
if ($conn->connect_error) {
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $logMsg = date('Y-m-d H:i:s') . " - DB Connection error: " . $conn->connect_error . PHP_EOL;
    @file_put_contents($logDir . '/db_errors.log', $logMsg, FILE_APPEND);

    // Trả về thông báo chung
    header('HTTP/1.1 500 Internal Server Error');
    die("❌ Kết nối CSDL thất bại. Vui lòng thử lại sau hoặc liên hệ quản trị viên.");
}

// Thiết lập mã hóa UTF-8
$conn->set_charset("utf8mb4"); 

// SIMPLE AUTH CHECK: nếu chưa đăng nhập, chuyển hướng về trang login
// Danh sách trang công khai (không yêu cầu login)
$public_pages = ['admin_login.php', 'admin_login_process.php'];
$current_script = basename($_SERVER['PHP_SELF']);
if (!in_array($current_script, $public_pages, true)) {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: admin_login.php');
        exit();
    }
}

?>