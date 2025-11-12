<?php
// Diagnostic: check sponsors.logo_url and clubs.image_url files existence
require_once __DIR__ . '/../../web_tennis/config.php';

function check_and_report($rows, $field) {
    foreach ($rows as $r) {
        $path = $r[$field];
        $exists = false;
        $reason = '';
        if (empty($path)) {
            $reason = 'empty';
        } elseif (stripos($path, 'http') === 0) {
            $exists = true; // remote URL - assume ok
            $reason = 'remote';
        } else {
            $local = __DIR__ . '/../../web_tennis/' . ltrim($path, '/');
            if (file_exists($local)) $exists = true;
            else $reason = 'missing';
        }
        echo "ID={$r['id']} | $field={$path} | exists=" . ($exists? 'YES':'NO') . " | $reason\n";
    }
}

// Sponsors
$sp = $conn->query("SELECT id, name, logo_url FROM sponsors");
$srows = $sp ? $sp->fetch_all(MYSQLI_ASSOC) : [];
echo "--- Sponsors ---\n";
check_and_report($srows, 'logo_url');

echo "\n--- Clubs ---\n";
$cl = $conn->query("SELECT id, name, city, image_url FROM clubs");
$crows = $cl ? $cl->fetch_all(MYSQLI_ASSOC) : [];
check_and_report($crows, 'image_url');

echo "\nDone.\n";

?>
