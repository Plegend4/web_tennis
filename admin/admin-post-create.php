<?php
// Bao gồm file config và kiểm tra phiên đăng nhập
include_once 'config.php'; 
// if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit(); }

$admin_page_title = "Thêm Bài viết Mới";

// --- XỬ LÝ FORM THÊM BÀI VIẾT (GIẢ ĐỊNH) ---
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Giả định xử lý lưu bài viết
    if (isset($_POST['post_title']) && !empty($_POST['post_title'])) {
        $title = htmlspecialchars($_POST['post_title']);
        $status = htmlspecialchars($_POST['post_status']);
        
        // Logic CSDL: INSERT INTO posts (title, content, status, ...) VALUES (...)
        
        if ($status === 'published') {
            $message = "<div class='alert success'>Xuất bản bài viết <strong>{$title}</strong> thành công!</div>";
        } elseif ($status === 'pending') {
            $message = "<div class='alert success'>Lưu bài viết <strong>{$title}</strong>, chờ duyệt!</div>";
        } else {
            $message = "<div class='alert success'>Lưu nháp bài viết <strong>{$title}</strong> thành công!</div>";
        }

        // Chuyển hướng hoặc làm mới trang
        // header("Location: admin_posts.php?status=draft");
        // exit();
    } else {
        $message = "<div class='alert error'>Tiêu đề bài viết không được để trống.</div>";
    }
}
$pending_posts = 70;
// --- DỮ LIỆU GIẢ ĐỊNH ---
// Lấy danh sách chuyên mục
$categories = [
    ['id' => 1, 'name' => 'Tin tức Giải đấu'],
    ['id' => 2, 'name' => 'Hoạt động CLB'],
    ['id' => 3, 'name' => 'Phân tích Chuyên môn'],
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
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    
    <style>
        /* CSS cho Editor Layout */
        .editor-grid { display: flex; gap: 20px; }
        .editor-main { flex: 3; } /* Chiếm 3/4 */
        .editor-sidebar { flex: 1; } /* Chiếm 1/4 */
        
        /* Main Content */
        .post-title-input { width: 100%; padding: 15px 10px; font-size: 2.2em; font-weight: 700; border: none; border-bottom: 1px solid #eee; margin-bottom: 20px; }
        .post-title-input:focus { outline: none; border-bottom: 1px solid var(--primary-color); }
        .ck-editor__editable_inline { min-height: 500px; border: 1px solid #ddd; padding: 20px; border-radius: 4px; }
        
        /* Sidebar Blocks */
        .editor-block { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); margin-bottom: 20px; }
        .editor-block h4 { font-size: 1.1em; font-weight: 600; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        
        /* Form Groups */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9em; }
        .form-group input[type="text"], .form-group textarea, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group small { display: block; margin-top: 5px; color: var(--text-light); font-size: 0.8em; }

        /* Nút Tác vụ */
        .btn-action { width: 100%; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; transition: opacity 0.3s; margin-top: 10px; }
        .btn-publish { background-color: #2ecc71; color: white; }
        .btn-draft { background-color: #f39c12; color: white; }
        .btn-preview { background-color: #3498db; color: white; }
        .btn-action:hover { opacity: 0.9; }

        /* Ảnh đại diện */
        .featured-image-preview { max-width: 100%; height: auto; border-radius: 4px; border: 1px solid #eee; margin-top: 10px; }
        
        /* Checkbox list */
        .checkbox-list label { display: block; margin-bottom: 5px; font-weight: 400; font-size: 0.9em; }
        .checkbox-list input[type="checkbox"] { margin-right: 5px; }

        /* Alerts */
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: 500; }
        .alert.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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
        
        <div class="admin-topbar" style="justify-content: flex-end;">
            <div class="user-info">
                <span class="username">Tác giả: Admin</span>
                <img src="img/admin_avatar.png" alt="Admin Avatar" class="avatar">
            </div>
        </div>
        <div class="dashboard-content-wrapper">
            <h2><?php echo htmlspecialchars($admin_page_title); ?></h2>
            
            <?php echo $message; // Hiển thị thông báo ?>

            <form action="admin_post_create.php" method="POST" enctype="multipart/form-data">
                <div class="editor-grid">
                    
                    <div class="editor-main">
                        <input type="text" id="post_title" name="post_title" class="post-title-input" placeholder="Nhập Tiêu đề bài viết tại đây" required>
                        
                        <div class="editor-block">
                            <label for="post_slug">Đường dẫn tĩnh (Slug)</label>
                            <input type="text" id="post_slug" name="post_slug" placeholder="duong-dan-tinh-cua-bai-viet">
                            <small>Tự động tạo từ tiêu đề. Chỉnh sửa nếu cần.</small>
                        </div>

                        <div class="editor-block">
                            <textarea id="editor" name="post_content">
                                <h3>Nội dung chính</h3>
                                <p>Bắt đầu viết nội dung bài viết...</p>
                            </textarea>
                        </div>

                        <div class="editor-block">
                            <h4><i class="fas fa-chart-line"></i> Cài đặt SEO</h4>
                            <div class="form-group">
                                <label for="seo_title">Tiêu đề SEO</label>
                                <input type="text" id="seo_title" name="seo_title" placeholder="Tối đa 70 ký tự (Để trống để lấy Tiêu đề bài viết)">
                            </div>
                            <div class="form-group">
                                <label for="seo_description">Mô tả SEO (Meta Description)</label>
                                <textarea id="seo_description" name="seo_description" placeholder="Tối đa 160 ký tự"></textarea>
                            </div>
                        </div>

                    </div>
                    
                    <div class="editor-sidebar">
                        
                        <div class="editor-block">
                            <h4>Tác vụ & Trạng thái</h4>
                            <div class="form-group">
                                <label for="post_status">Trạng thái</label>
                                <select id="post_status" name="post_status">
                                    <option value="draft">Nháp (Draft)</option>
                                    <option value="pending">Chờ Duyệt (Pending)</option>
                                    <option value="published">Xuất bản (Published)</option>
                                </select>
                            </div>
                            
                            <button type="submit" name="action" value="draft" class="btn-action btn-draft"><i class="fas fa-pencil-alt"></i> Lưu Nháp</button>
                            <button type="button" class="btn-action btn-preview"><i class="fas fa-eye"></i> Xem trước</button>
                            <button type="submit" name="action" value="publish" class="btn-action btn-publish"><i class="fas fa-paper-plane"></i> Xuất bản ngay</button>
                        </div>

                        <div class="editor-block">
                            <h4><i class="fas fa-folder-open"></i> Chuyên mục</h4>
                            <div class="checkbox-list">
                                <?php foreach ($categories as $cat): ?>
                                    <label>
                                        <input type="checkbox" name="post_categories[]" value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="editor-block">
                            <h4><i class="fas fa-tags"></i> Thẻ (Tags)</h4>
                            <input type="text" name="post_tags" placeholder="tennis, clb, giải đấu, ...">
                            <small>Ngăn cách bởi dấu phẩy.</small>
                        </div>
                        
                        <div class="editor-block">
                            <h4><i class="fas fa-image"></i> Ảnh Đại diện</h4>
                            <input type="file" name="featured_image" accept="image/*" onchange="previewImage(event)">
                            <img id="image_preview" class="featured-image-preview" src="" style="display:none;" alt="Ảnh đại diện">
                            <small>Kích thước tối ưu: 1200x675px.</small>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>

<script>
    // --- Khởi tạo CKEditor 5 ---
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            // Cấu hình toolbar đơn giản và phù hợp với tin tức
            toolbar: [ 
                'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 
                'insertTable', 'mediaEmbed', '|', 'undo', 'redo'
            ]
        } )
        .catch( error => {
            console.error( error );
        } );

    // --- Script tự động tạo Slug từ Tiêu đề ---
    function slugify(text) {
        return text.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, "") 
            .replace(/đ/g, 'd').replace(/Đ/g, 'D')
            .replace(/\s+/g, '-') 
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, ''); 
    }

    document.getElementById('post_title').addEventListener('input', function() {
        const title = this.value;
        document.getElementById('post_slug').value = slugify(title);
        
        // Tự động sao chép sang tiêu đề SEO nếu nó trống
        const seoTitle = document.getElementById('seo_title');
        if (seoTitle.value.trim() === '') {
            seoTitle.value = title;
        }
    });
    
    // --- Script Preview Ảnh Đại diện ---
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('image_preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>