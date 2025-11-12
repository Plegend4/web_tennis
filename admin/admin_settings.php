<?php
// Bao gồm file config và kiểm tra phiên đăng nhập
include_once 'config.php'; 
// if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit(); }

$admin_page_title = "Cài đặt Chung";
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general'; // Mặc định là tab 'general'

// --- XỬ LÝ FORM THAO TÁC (GIẢ ĐỊNH) ---
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Giả định xử lý lưu các cài đặt
    $tab_name = isset($_POST['settings_tab']) ? htmlspecialchars($_POST['settings_tab']) : 'general';

    // Logic CSDL: Cập nhật các giá trị vào bảng settings
    if ($tab_name === 'general') {
        // Xử lý Cài đặt Chung
        $site_name = htmlspecialchars($_POST['site_name']);
        $admin_email = htmlspecialchars($_POST['admin_email']);
        $message = "<div class='alert success'>Đã lưu Cài đặt Chung thành công!</div>";
    } elseif ($tab_name === 'seo') {
        // Xử lý Cài đặt SEO
        $home_title = htmlspecialchars($_POST['home_title']);
        $message = "<div class='alert success'>Đã lưu Cài đặt SEO thành công!</div>";
    }

    // Chuyển hướng lại về tab hiện tại để tránh gửi lại form
    header("Location: admin_settings.php?tab=$tab_name");
    exit();
}
$pending_posts = 70;
// --- DỮ LIỆU GIẢ ĐỊNH CHO CÀI ĐẶT ---
// Trong thực tế, bạn sẽ lấy dữ liệu từ bảng settings
$settings = [
    'site_name' => 'CLB Tennis DNT Việt Nam',
    'admin_email' => 'admin@tennisdnt.vn',
    'phone' => '+84 24 1234 5678',
    'address' => 'Số 123, Đường Trần Duy Hưng, Cầu Giấy, Hà Nội',
    'home_title' => 'CLB Tennis DNT Việt Nam | Tin tức, Lịch thi đấu và Hoạt động',
    'home_description' => 'Cập nhật tin tức tennis, lịch thi đấu, hoạt động của 34 CLB thành viên và các phân tích chuyên môn.',
    'posts_per_page' => 10,
    'default_timezone' => 'Asia/Ho_Chi_Minh',
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($admin_page_title); ?> - ADMIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin-style.css"> 
    <style>
        /* Các style bổ sung cho trang settings, nên được đặt trong admin_style.css */
        .settings-container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .tab-menu { border-bottom: 1px solid #ddd; margin-bottom: 20px; }
        .tab-menu a { display: inline-block; padding: 10px 15px; margin-right: 5px; color: var(--text-color); text-decoration: none; border: 1px solid transparent; border-bottom: none; }
        .tab-menu a.active { border-color: #ddd; border-bottom: 1px solid white; background-color: #f8f9fa; font-weight: 600; color: var(--primary-color); }
        .tab-content { padding-top: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.95em; color: #495057; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="number"], .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; height: 100px; }
        .form-group small { display: block; margin-top: 5px; color: var(--text-light); font-size: 0.85em; }

        .btn-save { background-color: #2ecc71; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600; transition: background-color 0.3s; }
        .btn-save:hover { background-color: #27ae60; }
        
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: 500; }
        .alert.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="admin-container">
    
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>DNT ADMIN</h3>
        </div>
        <ul class="sidebar-nav">
            <li class="active"><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="menu-title">Quản lý Tin tức</li>
            <li><a href="admin-posts.php"><i class="fas fa-file-alt"></i> Tất cả Bài viết</a></li>
            <li><a href="admin-posts.php?status=draft"><i class="fas fa-pencil-alt"></i> Bài viết Nháp</a></li>
            <li><a href="admin-posts.php?status=pending"><i class="fas fa-hourglass-half"></i> Chờ duyệt (<?php echo $pending_posts; ?>)</a></li>
            <li><a href="admin-post-create.php" class="new-post-btn"><i class="fas fa-plus"></i> Thêm Bài mới</a></li>
            
            <li class="menu-title">Dữ liệu & Cấu hình</li>
            <li><a href="admin_categories.php"><i class="fas fa-folder"></i> Quản lý Chuyên mục</a></li>
            <li><a href="admin-users.php"><i class="fas fa-users"></i> Quản lý Người dùng</a></li>
            <li><a href="admin_settings.php"><i class="fas fa-cog"></i> Cài đặt Chung</a></li>
            <li><a href="admin-sponsors.php"><i class="fas fa-handshake"></i> Đối tác & T. Trợ</a></li>
            <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </div>
    <div class="main-content">
        
        <div class="admin-topbar">
            <div class="search-box"><input type="text" placeholder="Tìm kiếm cài đặt..."></div>
            <div class="user-info">
                <a href="#"><i class="fas fa-bell"></i><span class="badge">3</span></a>
                <span class="username">Xin chào, Admin!</span>
                <img src="img/admin_avatar.png" alt="Admin Avatar" class="avatar">
            </div>
        </div>
        <div class="dashboard-content-wrapper">
            <h2><?php echo htmlspecialchars($admin_page_title); ?></h2>
            
            <?php if (!empty($message)) echo $message; // Hiển thị thông báo sau khi lưu ?>

            <div class="settings-container">
                
                <div class="tab-menu">
                    <a href="admin_settings.php?tab=general" class="<?php echo $current_tab === 'general' ? 'active' : ''; ?>">Thông tin Cơ bản</a>
                    <a href="admin_settings.php?tab=contact" class="<?php echo $current_tab === 'contact' ? 'active' : ''; ?>">Liên hệ & Vị trí</a>
                    <a href="admin_settings.php?tab=seo" class="<?php echo $current_tab === 'seo' ? 'active' : ''; ?>">Cài đặt SEO</a>
                    <a href="admin_settings.php?tab=display" class="<?php echo $current_tab === 'display' ? 'active' : ''; ?>">Hiển thị</a>
                </div>

                <div class="tab-content">
                    
                    <?php if ($current_tab === 'general'): ?>
                        <form action="admin_settings.php?tab=general" method="POST">
                            <input type="hidden" name="settings_tab" value="general">
                            
                            <div class="form-group">
                                <label for="site_name">Tên Website</label>
                                <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                                <small>Tên này sẽ xuất hiện trên thanh tiêu đề của trình duyệt.</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="admin_email">Email Quản trị</label>
                                <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email']); ?>" required>
                                <small>Địa chỉ email nhận thông báo từ hệ thống.</small>
                            </div>

                            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Lưu Cài đặt Cơ bản</button>
                        </form>
                    
                    <?php elseif ($current_tab === 'contact'): ?>
                        <form action="admin_settings.php?tab=contact" method="POST">
                            <input type="hidden" name="settings_tab" value="contact">
                            
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($settings['phone']); ?>">
                                <small>Số điện thoại chính hiển thị trên footer.</small>
                            </div>

                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($settings['address']); ?>">
                                <small>Địa chỉ văn phòng/trụ sở chính.</small>
                            </div>

                            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Lưu Cài đặt Liên hệ</button>
                        </form>

                    <?php elseif ($current_tab === 'seo'): ?>
                        <form action="admin_settings.php?tab=seo" method="POST">
                            <input type="hidden" name="settings_tab" value="seo">
                            
                            <h3>SEO Trang chủ</h3>
                            <div class="form-group">
                                <label for="home_title">Tiêu đề Trang chủ (Meta Title)</label>
                                <input type="text" id="home_title" name="home_title" value="<?php echo htmlspecialchars($settings['home_title']); ?>" maxlength="70">
                                <small>Tối đa 70 ký tự.</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="home_description">Mô tả Trang chủ (Meta Description)</label>
                                <textarea id="home_description" name="home_description" maxlength="160"><?php echo htmlspecialchars($settings['home_description']); ?></textarea>
                                <small>Tối đa 160 ký tự.</small>
                            </div>

                            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Lưu Cài đặt SEO</button>
                        </form>
                    
                    <?php elseif ($current_tab === 'display'): ?>
                        <form action="admin_settings.php?tab=display" method="POST">
                            <input type="hidden" name="settings_tab" value="display">
                            
                            <div class="form-group">
                                <label for="posts_per_page">Số lượng bài viết/trang</label>
                                <input type="number" id="posts_per_page" name="posts_per_page" value="<?php echo htmlspecialchars($settings['posts_per_page']); ?>" min="5" max="50">
                                <small>Số lượng bài viết tối đa hiển thị trên các trang danh sách tin tức.</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="default_timezone">Múi giờ mặc định</label>
                                <select id="default_timezone" name="default_timezone">
                                    <option value="Asia/Ho_Chi_Minh" <?php echo $settings['default_timezone'] === 'Asia/Ho_Chi_Minh' ? 'selected' : ''; ?>>(UTC+7) Asia/Ho Chi Minh</option>
                                    <option value="Asia/Bangkok" <?php echo $settings['default_timezone'] === 'Asia/Bangkok' ? 'selected' : ''; ?>>(UTC+7) Asia/Bangkok</option>
                                </select>
                                <small>Thiết lập múi giờ cho các thời điểm xuất bản bài viết.</small>
                            </div>

                            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Lưu Cài đặt Hiển thị</button>
                        </form>
                    
                    <?php endif; ?>

                </div>
            </div>
        </div>
        </div>
    </div>

</body>
</html>