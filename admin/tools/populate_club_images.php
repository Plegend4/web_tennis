<?php
// Populates clubs.image_url using slugified city (or name) if image_url is empty.
require_once __DIR__ . '/../../web_tennis/config.php';

function slugify_local($text) {
    // reuse slugify from web_tennis/config.php if available
    if (function_exists('slugify')) return slugify($text);
    $map = array(
        'à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
        'è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
        'ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
        'ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
        'ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
        'ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
        'đ'=>'d',
    );
    $text = strtr($text, $map);
    $text = preg_replace('~[^A-Za-z0-9]+~', '-', $text);
    $text = strtolower(trim($text, '-'));
    return $text;
}

$use_field = 'city'; // default: use city to build slug
$confirm = true; // set to false to run as dry-run

$sql = "SELECT id, name, city, image_url FROM clubs";
$res = $conn->query($sql);
if (!$res) {
    echo "DB query failed\n";
    exit;
}
$updates = 0;
while ($row = $res->fetch_assoc()) {
    if (!empty($row['image_url'])) continue; // skip existing
    $base = !empty($row[$use_field]) ? $row[$use_field] : $row['name'];
    $slug = slugify_local($base);
    $candidate = 'img/clb-' . $slug . '.png';
    echo "Will set id={$row['id']} -> $candidate\n";
    if (!$confirm) continue;
    $stmt = $conn->prepare("UPDATE clubs SET image_url = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('si', $candidate, $row['id']);
        $stmt->execute();
        $updates++;
    }
}

echo "Done. Updated $updates rows.\n";

?>
