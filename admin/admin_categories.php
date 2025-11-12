<?php
// Bao gồm file config và kiểm tra phiên đăng nhập
include_once 'config.php'; 
// if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit(); }

$admin_page_title = "Quản lý Chuyên mục";

// --- XỬ LÝ FORM THAO TÁC (GIẢ ĐỊNH) ---
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Giả định xử lý thêm/sửa chuyên mục
    if (isset($_POST['category_name']) && !empty($_POST['category_name'])) {
        $name = htmlspecialchars($_POST['category_name']);
        $slug = htmlspecialchars($_POST['category_slug']);
        $description = htmlspecialchars($_POST['category_description']);
        $is_edit = isset($_POST['category_id']) && !empty($_POST['category_id']);
        
        if ($is_edit) {
            // Logic CSDL: UPDATE categories SET ... WHERE id = $_POST['category_id']
            $message = "<div class='alert success'>Cập nhật chuyên mục <strong>{$name}</strong> thành công!</div>";
        } else {
            // Logic CSDL: INSERT INTO categories (name, slug, description) VALUES (...)
            $message = "<div class='alert success'>Thêm mới chuyên mục <strong>{$name}</strong> thành công!</div>";
        }
    } else {
        $message = "<div class='alert error'>Vui lòng nhập Tên Chuyên mục.</div>";
    }
}
$pending_posts = 70;
// --- DỮ LIỆU GIẢ ĐỊNH CHO BẢNG CHUYÊN MỤC ---
// Trong thực tế, bạn sẽ chạy câu truy vấn SQL: SELECT * FROM categories ORDER BY id ASC
$categories = [
    ['id' => 1, 'name' => 'Tin tức Giải đấu', 'slug' => 'giai-dau', 'post_count' => 450, 'description' => 'Các tin tức về giải đấu trong nước và quốc tế.'],
    ['id' => 2, 'name' => 'Hoạt động CLB', 'slug' => 'hoat-dong', 'post_count' => 210, 'description' => 'Các hoạt động, sự kiện nội bộ của CLB.'],
    ['id' => 3, 'name' => 'Phân tích Chuyên môn', 'slug' => 'chuyen-mon', 'post_count' => 180, 'description' => 'Bài viết về kỹ thuật, chiến thuật và phân tích trận đấu.'],
    ['id' => 4, 'name' => 'Video Hay', 'slug' => 'video', 'post_count' => 100, 'description' => 'Các video hướng dẫn và tổng hợp.', 'is_default' => true],
];

// --- Chuẩn bị dữ liệu cho chế độ chỉnh sửa (nếu có tham số edit_id) ---
$edit_category = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    // Logic CSDL: SELECT * FROM categories WHERE id = $edit_id
    
    // Giả định tìm thấy trong mảng dữ liệu giả định
    foreach ($categories as $cat) {
        if ($cat['id'] === $edit_id) {
            $edit_category = $cat;
            break;
        }
    }
    $admin_page_title = "Chỉnh sửa Chuyên mục";
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
        .categories-grid { display: flex; gap: 30px; }
        .form-column { flex: 1; }
        .table-column { flex: 2; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9em; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; height: 100px; }
        .btn-submit { background-color: <?php echo $edit_category ? '#f39c12' : '#2ecc71'; ?>; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600; transition: background-color 0.3s; }
        .btn-submit:hover { opacity: 0.9; }
        .btn-cancel-edit { background: #ccc; margin-left: 10px; }

        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: 500; }
        .alert.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .cat-table-container { background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow-x: auto; }
        .cat-table { width: 100%; border-collapse: collapse; }
        .cat-table th, .cat-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.9em; }
        .cat-table th { background-color: #f8f9fa; font-weight: 600; }
        .cat-table tbody tr:hover { background-color: #f9f9f9; }

        .action-icon { color: #6c757d; margin-right: 10px; cursor: pointer; }
        .action-icon:hover { color: var(--primary-color); }
        .action-icon.trash:hover { color: #e74c3c; }
        .cat-count { color: var(--primary-color); font-weight: 600; }
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
            <div class="search-box"><input type="text" placeholder="Tìm kiếm..."></div>
            <div class="user-info">
                <a href="#"><i class="fas fa-bell"></i><span class="badge">3</span></a>
                <span class="username">Xin chào, Admin!</span>
                <img src="img/admin_avatar.png" alt="Admin Avatar" class="avatar">
            </div>
        </div>
        <div class="dashboard-content-wrapper">
            <h2><?php echo htmlspecialchars($admin_page_title); ?></h2>
            
            <?php echo $message; // Hiển thị thông báo (thành công/lỗi) ?>

            <div class="categories-grid">
                
                <div class="form-column widget">
                    <h3><?php echo $edit_category ? 'Chỉnh sửa Chuyên mục: ' . htmlspecialchars($edit_category['name']) : 'Thêm Chuyên mục Mới'; ?></h3>
                    
                    <form action="admin_categories.php" method="POST">
                        <?php if ($edit_category): ?>
                            <input type="hidden" name="category_id" value="<?php echo $edit_category['id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="category_name">Tên Chuyên mục (*)</label>
                            <input type="text" id="category_name" name="category_name" 
                                value="<?php echo $edit_category ? htmlspecialchars($edit_category['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_slug">Slug (Đường dẫn tĩnh)</label>
                            <input type="text" id="category_slug" name="category_slug" 
                                value="<?php echo $edit_category ? htmlspecialchars($edit_category['slug']) : ''; ?>" 
                                placeholder="tin-tuc-giai-dau">
                        </div>
                        
                        <div class="form-group">
                            <label for="category_description">Mô tả</label>
                            <textarea id="category_description" name="category_description" 
                                placeholder="Mô tả ngắn về chuyên mục này (Hỗ trợ SEO)">
                                <?php echo $edit_category ? htmlspecialchars($edit_category['description']) : ''; ?>
                            </textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> <?php echo $edit_category ? 'Cập nhật Chuyên mục' : 'Thêm Mới'; ?>
                        </button>

                        <?php if ($edit_category): ?>
                            <a href="admin_categories.php" class="btn-submit btn-cancel-edit">Hủy</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-column cat-table-container">
                    <table class="cat-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Chuyên mục</th>
                                <th>Slug</th>
                                <th>Số Bài viết</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><?php echo $cat['id']; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                            <?php if (isset($cat['is_default']) && $cat['is_default']): ?>
                                                <small>(Mặc định)</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                                        <td><span class="cat-count"><?php echo number_format($cat['post_count']); ?></span></td>
                                        <td>
                                            <a href="admin_categories.php?edit_id=<?php echo $cat['id']; ?>" title="Sửa"><i class="fas fa-edit action-icon"></i></a>
                                            <button onclick="return confirm('Bạn có chắc muốn xóa chuyên mục này?')" title="Xóa" style="border:none; background:none; padding:0;">
                                                <i class="fas fa-trash-alt action-icon trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="no-data">Chưa có chuyên mục nào được tạo.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        </div>
    </div>

<script>
    // Tự động tạo Slug khi người dùng nhập Tên Chuyên mục (Chỉ trong chế độ Thêm mới)
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('category_name');
        const slugInput = document.getElementById('category_slug');
        const editIdInput = document.querySelector('input[name="category_id"]');

        if (nameInput && slugInput && !editIdInput) { // Chỉ áp dụng khi thêm mới
            nameInput.addEventListener('input', function() {
                // Hàm chuyển đổi tiếng Việt có dấu thành không dấu, cách nhau bằng gạch ngang
                function slugify(text) {
                    return text.toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, "") // Loại bỏ dấu tiếng Việt
                        .replace(/đ/g, 'd').replace(/Đ/g, 'D') // Xử lý chữ Đ
                        .replace(/\s+/g, '-') // Thay thế khoảng trắng bằng gạch ngang
                        .replace(/[^\w\-]+/g, '') // Loại bỏ tất cả ký tự không phải chữ, số, hoặc gạch ngang
                        .replace(/\-\-+/g, '-') // Loại bỏ nhiều gạch ngang liên tiếp
                        .replace(/^-+/, '') // Loại bỏ gạch ngang ở đầu
                        .replace(/-+$/, ''); // Loại bỏ gạch ngang ở cuối
                }
                slugInput.value = slugify(this.value);
            });
        }
    });
</script>

</body>
</html>