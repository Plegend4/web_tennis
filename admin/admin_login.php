<?php
// Simple admin login page (minimal). Change credentials below after first login.
require_once __DIR__ . '/config.php'; // config.php allows access to this page (it's in public_pages)

// Default credentials (CHANGE THESE in production)
$ADM_USER = 'admin';
$ADM_PASS = 'admin'; // insecure default; change immediately

// If already logged in, redirect to admin dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = isset($_POST['username']) ? trim($_POST['username']) : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';

    // Simple check - in production replace with DB-backed users and hashed passwords
    if ($user === $ADM_USER && $pass === $ADM_PASS) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user;
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Sai tên đăng nhập hoặc mật khẩu.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="admin-style.css">
    <style>
        body { background: #f5f5f5; font-family: Poppins, sans-serif; }
        .login-box { width: 360px; margin: 8% auto; background: white; padding: 24px; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .login-box h2 { margin-bottom: 12px; }
        .form-group { margin-bottom: 12px; }
        input[type=text], input[type=password] { width:100%; padding:8px 10px; border:1px solid #ddd; border-radius:4px; }
        .btn { background:#1a1a1a; color:white; padding:10px 16px; border-radius:6px; border:none; cursor:pointer; }
        .alert { background:#fdecea; color:#842029; padding:10px; border-radius:6px; margin-bottom:12px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Đăng nhập quản trị</h2>
        <?php if ($error): ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Tài khoản</label>
                <input id="username" name="username" type="text" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input id="password" name="password" type="password" required>
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
                <button class="btn" type="submit">Đăng nhập</button>
                <a href="../index.php">Về trang công khai</a>
            </div>
        </form>
        <p style="margin-top:12px; font-size:0.85rem; color:#666">Mật khẩu mặc định là <strong>admin</strong>. Hãy đổi ngay sau khi đăng nhập.</p>
    </div>
</body>
</html>
