<?php
// Bao gồm file config và kiểm tra phiên đăng nhập
include_once 'config.php'; 
// if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit(); }

$admin_page_title = "Quản lý Đối tác & Nhà tài trợ";

// --- XỬ LÝ FORM THAO TÁC (GIẢ ĐỊNH) ---
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Giả định xử lý thêm/sửa nhà tài trợ
    if (isset($_POST['sponsor_name']) && !empty($_POST['sponsor_name'])) {
        $name = htmlspecialchars($_POST['sponsor_name']);
        $level = htmlspecialchars($_POST['sponsor_level']);
        $is_edit = isset($_POST['sponsor_id']) && !empty($_POST['sponsor_id']);
        
        if ($is_edit) {
            // Logic CSDL: UPDATE sponsors SET ... WHERE id = $_POST['sponsor_id']
            $message = "<div class='alert success'>Cập nhật nhà tài trợ <strong>{$name}</strong> thành công!</div>";
        } else {
            // Logic CSDL: INSERT INTO sponsors (name, level, logo_url) VALUES (...)
            $message = "<div class='alert success'>Thêm mới nhà tài trợ <strong>{$name}</strong> thành công!</div>";
        }
    } else {
        $message = "<div class='alert error'>Vui lòng nhập Tên Nhà tài trợ.</div>";
    }
}
$pending_posts = 70;
// --- DỮ LIỆU GIẢ ĐỊNH CHO BẢNG NHÀ TÀI TRỢ ---
// Trong thực tế, bạn sẽ chạy câu truy vấn SQL: SELECT * FROM sponsors ORDER BY level DESC, name ASC
$sponsors = [
    ['id' => 1, 'name' => 'Công ty TNHH Dịch vụ Du lịch A', 'level' => 'Diamond', 'logo_url' => 'logo-a.png'],
    ['id' => 2, 'name' => 'Ngân hàng TMCP Quốc tế B', 'level' => 'Gold', 'logo_url' => 'logo-b.png'],
    ['id' => 3, 'name' => 'Thương hiệu Thời trang Thể thao C', 'level' => 'Gold', 'logo_url' => 'logo-c.png'],
    ['id' => 4, 'name' => 'Chuỗi Cà phê & Ăn uống D', 'level' => 'Silver', 'logo_url' => 'logo-d.png'],
    ['id' => 5, 'name' => 'Sản phẩm dinh dưỡng E', 'level' => 'Bronze', 'logo_url' => 'logo-e.png'],
];

// Định nghĩa các cấp độ tài trợ để sử dụng trong Form
$levels = [
    'Diamond' => 'Kim cương',
    'Gold' => 'Vàng',
    'Silver' => 'Bạc',
    'Bronze' => 'Đồng',
    'Partner' => 'Đối tác',
];

// --- Chuẩn bị dữ liệu cho chế độ chỉnh sửa (nếu có tham số edit_id) ---
$edit_sponsor = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    // Logic CSDL: SELECT * FROM sponsors WHERE id = $edit_id
    
    // Giả định tìm thấy trong mảng dữ liệu giả định
    foreach ($sponsors as $sponsor) {
        if ($sponsor['id'] === $edit_id) {
            $edit_sponsor = $sponsor;
            break;
        }
    }
    $admin_page_title = "Chỉnh sửa Nhà tài trợ";
}
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
        /* CSS cho bố cục Form và Bảng */
        .sponsor-grid { display: flex; gap: 30px; }
        .form-column { flex: 1; }
        .table-column { flex: 2; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9em; }
        .form-group input[type="text"], .form-group input[type="url"], .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; height: 100px; }

        /* Nút */
        .btn-submit { background-color: <?php echo $edit_sponsor ? '#f39c12' : '#2ecc71'; ?>; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600; transition: background-color 0.3s; }
        .btn-submit:hover { opacity: 0.9; }
        .btn-cancel-edit { background: #ccc; margin-left: 10px; }

        /* Thông báo */
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: 500; }
        .alert.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Bảng */
        .sponsor-table-container { background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow-x: auto; }
        .sponsor-table { width: 100%; border-collapse: collapse; }
        .sponsor-table th, .sponsor-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.9em; }
        .sponsor-table th { background-color: #f8f9fa; font-weight: 600; }
        .sponsor-table tbody tr:hover { background-color: #f9f9f9; }
        
        /* Cấp độ */
        .level-badge { padding: 4px 8px; border-radius: 4px; color: white; font-weight: 600; font-size: 0.8em; }
        .level-Diamond { background-color: #3498db; }
        .level-Gold { background-color: #f39c12; }
        .level-Silver { background-color: #95a5a6; }
        .level-Bronze { background-color: #e67e22; }
        .level-Partner { background-color: #2c3e50; }
        
        /* Logo Preview */
        .logo-preview { max-width: 50px; height: auto; vertical-align: middle; margin-right: 10px; border: 1px solid #eee; border-radius: 4px; }
        .table-logo-preview { max-width: 40px; height: auto; }
        
        /* Thao tác */
        .action-icon { color: #6c757d; margin-right: 10px; cursor: pointer; }
        .action-icon:hover { color: var(--primary-color); }
        .action-icon.trash:hover { color: #e74c3c; }
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
            <div class="search-box"><input type="text" placeholder="Tìm kiếm nhà tài trợ..."></div>
            <div class="user-info">
                <a href="#"><i class="fas fa-bell"></i><span class="badge">3</span></a>
                <span class="username">Xin chào, Admin!</span>
                <img src="img/admin_avatar.png" alt="Admin Avatar" class="avatar">
            </div>
        </div>
        <div class="dashboard-content-wrapper">
            <h2><?php echo htmlspecialchars($admin_page_title); ?></h2>
            
            <?php echo $message; // Hiển thị thông báo (thành công/lỗi) ?>

            <div class="sponsor-grid">
                
                <div class="form-column widget">
                    <h3><?php echo $edit_sponsor ? 'Chỉnh sửa Nhà tài trợ: ' . htmlspecialchars($edit_sponsor['name']) : 'Thêm Nhà tài trợ Mới'; ?></h3>
                    
                    <form action="admin_sponsors.php" method="POST" enctype="multipart/form-data">
                        <?php if ($edit_sponsor): ?>
                            <input type="hidden" name="sponsor_id" value="<?php echo $edit_sponsor['id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="sponsor_name">Tên Nhà tài trợ (*)</label>
                            <input type="text" id="sponsor_name" name="sponsor_name" 
                                value="<?php echo $edit_sponsor ? htmlspecialchars($edit_sponsor['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="sponsor_level">Cấp độ Tài trợ (*)</label>
                            <select id="sponsor_level" name="sponsor_level" required>
                                <?php foreach ($levels as $key => $value): ?>
                                    <option value="<?php echo $key; ?>" 
                                        <?php echo ($edit_sponsor && $edit_sponsor['level'] === $key) ? 'selected' : ''; ?>>
                                        <?php echo $value; ?> (<?php echo $key; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sponsor_logo">Logo Nhà tài trợ</label>
                            <input type="file" id="sponsor_logo" name="sponsor_logo">
                            <?php if ($edit_sponsor && $edit_sponsor['logo_url']): ?>
                                <small>Logo hiện tại:</small>
                                <img src="img/logos/<?php echo htmlspecialchars($edit_sponsor['logo_url']); ?>" alt="Logo" class="logo-preview">
                            <?php endif; ?>
                            <small>Định dạng .png, .jpg (Tốt nhất là nền trong suốt).</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="sponsor_link">Liên kết Website</label>
                            <input type="url" id="sponsor_link" name="sponsor_link" 
                                value="<?php echo $edit_sponsor ? htmlspecialchars($edit_sponsor['logo_url']) : ''; ?>" 
                                placeholder="https://websitecuataitro.com">
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> <?php echo $edit_sponsor ? 'Cập nhật' : 'Thêm Mới'; ?>
                        </button>

                        <?php if ($edit_sponsor): ?>
                            <a href="admin_sponsors.php" class="btn-submit btn-cancel-edit">Hủy</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-column sponsor-table-container">
                    <table class="sponsor-table">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Tên Nhà tài trợ</th>
                                <th>Cấp độ</th>
                                <th>Liên kết</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($sponsors)): ?>
                                <?php foreach ($sponsors as $sponsor): ?>
                                    <tr>
                                        <td>
                                            <img src="img/logos/<?php echo htmlspecialchars($sponsor['logo_url']); ?>" alt="<?php echo htmlspecialchars($sponsor['name']); ?> Logo" class="table-logo-preview">
                                        </td>
                                        <td><?php echo htmlspecialchars($sponsor['name']); ?></td>
                                        <td>
                                            <span class="level-badge level-<?php echo $sponsor['level']; ?>">
                                                <?php echo $levels[$sponsor['level']]; ?>
                                            </span>
                                        </td>
                                        <td><a href="<?php echo htmlspecialchars($sponsor['logo_url']); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a></td>
                                        <td>
                                            <a href="admin_sponsors.php?edit_id=<?php echo $sponsor['id']; ?>" title="Sửa"><i class="fas fa-edit action-icon"></i></a>
                                            <button onclick="return confirm('Bạn có chắc muốn xóa nhà tài trợ này?')" title="Xóa" style="border:none; background:none; padding:0;">
                                                <i class="fas fa-trash-alt action-icon trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="no-data">Chưa có nhà tài trợ nào được thêm.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        </div>
    </div>

</body>
</html>