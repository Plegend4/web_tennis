<?php
// Print club slug list (recommend filenames like clb-{slug}.png)
require_once __DIR__ . '/../../web_tennis/config.php';

function slugify_local($text) {
    if (function_exists('slugify')) return slugify($text);
    $map = array('à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ă'=>'a','è'=>'e','é'=>'e','ê'=>'e','ì'=>'i','í'=>'i','ò'=>'o','ó'=>'o','ô'=>'o','ơ'=>'o','ù'=>'u','ú'=>'u','ư'=>'u','ỳ'=>'y','ý'=>'y','đ'=>'d');
    $text = strtr($text, $map);
    $text = preg_replace('~[^A-Za-z0-9]+~', '-', $text);
    return strtolower(trim($text, '-'));
}

$res = $conn->query("SELECT id, name, city FROM clubs ORDER BY city, name");
if (!$res) { echo "DB error\n"; exit; }
$rows = $res->fetch_all(MYSQLI_ASSOC);

foreach ($rows as $r) {
    $slug = slugify_local($r['city'] ?: $r['name']);
    echo "id={$r['id']} | name={$r['name']} | slug=clb-{$slug} | filename=img/clb-{$slug}.png\n";
}

?>
