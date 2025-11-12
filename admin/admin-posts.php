<?php
// Bao gồm file config và kiểm tra phiên đăng nhập
include_once 'config.php'; 
// if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit(); }

// --- Lấy tham số trạng thái từ URL ---
// Sanitize status filter - only allow a small whitelist to avoid SQL injection
$allowed_statuses = ['all', 'draft', 'pending', 'published'];
$status_filter = isset($_GET['status']) ? strtolower($_GET['status']) : 'all';
if (!in_array($status_filter, $allowed_statuses, true)) {
    $status_filter = 'all';
}

// Thiết lập tiêu đề và truy vấn CSDL dựa trên trạng thái
switch ($status_filter) {
    case 'draft':
        $admin_page_title = "Bài viết Nháp";
        $sql_condition = "WHERE status = 'draft'";
        break;
    case 'pending':
        $admin_page_title = "Bài viết Chờ duyệt";
        $sql_condition = "WHERE status = 'pending'";
        break;
    case 'published':
        $admin_page_title = "Bài viết Đã xuất bản";
        $sql_condition = "WHERE status = 'published'";
        break;
    case 'all':
    default:
        $admin_page_title = "Tất cả Bài viết";
        $sql_condition = ""; // Lấy tất cả
        break;
}
$pending_posts = 70;
// --- DỮ LIỆU GIẢ ĐỊNH CHO BẢNG TIN TỨC ---
// Trong thực tế, bạn sẽ chạy câu truy vấn SQL.
// IMPORTANT: Do NOT inject $status_filter directly into SQL. Use a prepared statement or controlled mapping.
// Example (if you had a real DB table):
// if ($status_filter === 'all') {
//     $sql = "SELECT id, title, category_name, author, views, publish_date, status FROM posts ORDER BY publish_date DESC";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// } else {
//     $sql = "SELECT id, title, category_name, author, views, publish_date, status FROM posts WHERE status = ? ORDER BY publish_date DESC";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('s', $status_filter);
//     $stmt->execute();
//     $posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// }

$posts = [
    ['id' => 101, 'title' => 'Tuyển tập 10 cú ace đẹp mắt nhất tuần qua', 'category' => 'Tin tức Giải đấu', 'author' => 'Nguyễn Văn A', 'views' => 2100, 'date' => '2025-11-10', 'status' => 'published'],
    ['id' => 102, 'title' => 'Phân tích chiến thuật của Nadal tại Roland Garros', 'category' => 'Phân tích Chuyên môn', 'author' => 'Trần Thị B', 'views' => 1850, 'date' => '2025-11-09', 'status' => 'published'],
    ['id' => 103, 'title' => 'Hướng dẫn chọn vợt tennis phù hợp cho người mới', 'category' => 'Hướng dẫn', 'author' => 'Phạm Văn C', 'views' => 0, 'date' => '2025-11-08', 'status' => 'draft'],
    ['id' => 104, 'title' => 'Lịch trình giải đấu DNT Open 2025 (Cần duyệt)', 'category' => 'Tin tức Giải đấu', 'author' => 'Nguyễn Văn A', 'views' => 0, 'date' => '2025-11-07', 'status' => 'pending'],
    ['id' => 105, 'title' => 'Top 5 sân tennis đẹp nhất Hà Nội (Nháp)', 'category' => 'Hoạt động', 'author' => 'Trần Thị B', 'views' => 0, 'date' => '2025-11-06', 'status' => 'draft'],
];

// Lọc dữ liệu giả định theo status_filter
if ($status_filter !== 'all') {
    $posts = array_filter($posts, function($post) use ($status_filter) {
        return $post['status'] === $status_filter;
    });
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

            <div class="post-controls">
                <div class="bulk-actions">
                    <select name="action" id="bulk-action">
                        <option value="">Chọn hành động</option>
                        <option value="publish">Xuất bản</option>
                        <option value="draft">Chuyển thành Nháp</option>
                        <option value="delete">Xóa</option>
                    </select>
                    <button class="btn-apply">Áp dụng</button>
                </div>

                <div class="filters">
                    <select name="category" id="filter-category">
                        <option value="all">Tất cả Chuyên mục</option>
                        <option value="news">Tin tức Giải đấu</option>
                        <option value="activity">Hoạt động</option>
                        <option value="analysis">Phân tích Chuyên môn</option>
                    </select>
                    <input type="date" id="filter-date">
                    <button class="btn-filter"><i class="fas fa-filter"></i> Lọc</button>
                </div>
            </div>

            <div class="post-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Tiêu đề Bài viết</th>
                            <th>Chuyên mục</th>
                            <th>Tác giả</th>
                            <th>Lượt xem <i class="fas fa-sort"></i></th>
                            <th>Ngày XB / Cập nhật <i class="fas fa-sort"></i></th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($posts) > 0): ?>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><input type="checkbox" name="post_id[]" value="<?php echo $post['id']; ?>"></td>
                                <td>
                                    <a href="admin_post_edit.php?id=<?php echo $post['id']; ?>" class="post-title-link">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($post['category']); ?></td>
                                <td><?php echo htmlspecialchars($post['author']); ?></td>
                                <td><?php echo number_format($post['views']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($post['date'])); ?></td>
                                <td>
                                    <?php 
                                        $status_class = match($post['status']) {
                                            'published' => 'status-published',
                                            'draft' => 'status-draft',
                                            'pending' => 'status-pending',
                                            default => 'status-draft',
                                        };
                                        $status_text = match($post['status']) {
                                            'published' => 'Đã Xuất bản',
                                            'draft' => 'Nháp',
                                            'pending' => 'Chờ Duyệt',
                                            default => 'Nháp',
                                        };
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td>
                                    <a href="admin_post_edit.php?id=<?php echo $post['id']; ?>" title="Sửa"><i class="fas fa-edit action-icon"></i></a>
                                    <a href="../post.php?id=<?php echo $post['id']; ?>" target="_blank" title="Xem trước"><i class="fas fa-eye action-icon"></i></a>
                                    <button class="btn-delete" data-id="<?php echo $post['id']; ?>" title="Xóa"><i class="fas fa-trash-alt action-icon"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="no-data">Không tìm thấy bài viết nào có trạng thái "<?php echo htmlspecialchars($admin_page_title); ?>"</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-footer">
                <span class="record-count">Hiển thị 1-10 trên 1250 bài viết</span>
                <div class="pagination-links">
                    <a href="#" class="page-link disabled">Trước</a>
                    <a href="#" class="page-link active">1</a>
                    <a href="#" class="page-link">2</a>
                    <a href="#" class="page-link">...</a>
                    <a href="#" class="page-link">125</a>
                    <a href="#" class="page-link">Sau</a>
                </div>
            </div>

        </div>
        </div>
    </div>

<script>
    // --- Script cho chức năng Chọn tất cả ---
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.querySelectorAll('input[name="post_id[]"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>

</body>
</html>