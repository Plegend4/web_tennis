<?php
/**
 * File: config.php
 * Thiết lập kết nối cơ sở dữ liệu MySQL (hoặc MariaDB) cho Laragon
 */

// Security headers for public site (minimal). Adjust if you load resources from other domains.
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
// Minimal CSP allowing self, https and data for images. Keep it permissive for now and tighten later.
header("Content-Security-Policy: default-src 'self' https:; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline' https:");

$servername = "localhost"; 
$username = "root";       
$password = "";           // Mật khẩu CSDL (thường là rỗng)
$dbname = "db_tennis_club";  // TÊN CSDL ĐÃ ĐƯỢC CẬP NHẬT

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    // Ghi log chi tiết vào file logs/db_errors.log (nếu có thể)
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $logMsg = date('Y-m-d H:i:s') . " - DB Connection error: " . $conn->connect_error . PHP_EOL;
    @file_put_contents($logDir . '/db_errors.log', $logMsg, FILE_APPEND);

    // Thông báo chung cho người dùng (không tiết lộ chi tiết nội bộ)
    die("❌ Kết nối CSDL thất bại. Vui lòng thử lại sau hoặc liên hệ quản trị viên.");
}

// Thiết lập mã hóa UTF-8
$conn->set_charset("utf8mb4"); 

/**
 * Kiểm tra và trả về URL ảnh an toàn.
 * Cho phép chỉ http://, https:// và data:image/* (nếu cần). Trả về empty string nếu không hợp lệ.
 */
function safe_image_url($url) {
    $url = trim($url);
    if (empty($url)) return '';

    // Cho phép data URI cho các hình nhỏ (tùy chọn). Nếu bạn không muốn, loại bỏ phần này.
    if (stripos($url, 'data:image/') === 0) {
        return $url;
    }

    // Kiểm tra scheme
    // Nếu là URL đầy đủ với scheme thì chỉ cho phép http/https
    $parts = parse_url($url);
    if ($parts === false) return '';
    $scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : '';
    if ($scheme !== '') {
        if (in_array($scheme, ['http', 'https'])) {
            return $url;
        }
        // Bất kỳ scheme khác (javascript:, data: đã xử lý ở trên) -> không cho phép
        return '';
    }

    // Nếu không có scheme, cho phép đường dẫn tương đối hoặc bắt đầu bằng '/'
    // Bảo đảm không bắt đầu bằng '//' (protocol-relative) để tránh rủi ro
    if (strpos($url, '//') === 0) return '';
    // Chuẩn hóa và trả về
    return $url;
}

/**
 * Create a filename-friendly slug from a string (for matching images)
 */
function slugify($text) {
    // Thay thế các ký tự tiếng Việt thường gặp bằng phiên bản ASCII
    $map = array(
        'à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
        'è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
        'ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
        'ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
        'ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
        'ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
        'đ'=>'d',
        'À'=>'a','Á'=>'a','Ạ'=>'a','Ả'=>'a','Ã'=>'a','Â'=>'a','Ầ'=>'a','Ấ'=>'a','Ậ'=>'a','Ẩ'=>'a','Ẫ'=>'a','Ă'=>'a','Ằ'=>'a','Ắ'=>'a','Ặ'=>'a','Ẳ'=>'a','Ẵ'=>'a',
        'È'=>'e','É'=>'e','Ẹ'=>'e','Ẻ'=>'e','Ẽ'=>'e','Ê'=>'e','Ề'=>'e','Ế'=>'e','Ệ'=>'e','Ể'=>'e','Ễ'=>'e',
        'Ì'=>'i','Í'=>'i','Ị'=>'i','Ỉ'=>'i','Ĩ'=>'i',
        'Ò'=>'o','Ó'=>'o','Ọ'=>'o','Ỏ'=>'o','Õ'=>'o','Ô'=>'o','Ồ'=>'o','Ố'=>'o','Ộ'=>'o','Ổ'=>'o','Ỗ'=>'o','Ơ'=>'o','Ờ'=>'o','Ớ'=>'o','Ợ'=>'o','Ở'=>'o','Ỡ'=>'o',
        'Ù'=>'u','Ú'=>'u','Ụ'=>'u','Ủ'=>'u','Ũ'=>'u','Ư'=>'u','Ừ'=>'u','Ứ'=>'u','Ự'=>'u','Ử'=>'u','Ữ'=>'u',
        'Ỳ'=>'y','Ý'=>'y','Ỵ'=>'y','Ỷ'=>'y','Ỹ'=>'y',
        'Đ'=>'d'
    );

    $text = strtr($text, $map);
    // Replace non-letter or digits by -
    $text = preg_replace('~[^A-Za-z0-9]+~', '-', $text);
    $text = strtolower(trim($text, '-'));
    return $text;
}
?>