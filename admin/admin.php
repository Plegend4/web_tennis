<?php
// Bao gồm file config để kết nối CSDL và thiết lập các biến cần thiết
// Bạn cần đảm bảo file 'config.php' thiết lập biến $conn
include_once 'config.php'; 

// Thiết lập tiêu đề trang
$admin_page_title = "Dashboard Quản lý Tin tức";

// Kiểm tra phiên đăng nhập (Đây là mã giả định, bạn cần triển khai cơ chế đăng nhập thực tế)
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: admin_login.php");
//     exit();
// }

// --- DỮ LIỆU GIẢ ĐỊNH CHO DASHBOARD ---
// Cần thay thế bằng các câu lệnh truy vấn CSDL thực tế của bạn
$total_posts = 1250;
$published_posts = 980;
$draft_posts = 200;
$pending_posts = 70;
$weekly_views = '12,500'; // Có thể lấy từ bảng thống kê hoặc Google Analytics
$new_comments = 45;

// Dữ liệu giả định cho bài viết nổi bật
$top_posts = [
    ['title' => 'Tuyển tập 10 cú ace đẹp mắt nhất tuần qua', 'views' => '2,100'],
    ['title' => 'Phân tích chiến thuật của Nadal tại Roland Garros', 'views' => '1,850'],
    ['title' => 'Hướng dẫn chọn vợt tennis phù hợp cho người mới', 'views' => '1,520'],
    ['title' => 'Lịch trình giải đấu DNT Open 2025', 'views' => '1,300'],
];
// Dữ liệu giả định cho hoạt động gần đây
$recent_activities = [
    ['type' => 'Xuất bản', 'content' => 'Bài viết "Giải mã cú giao bóng xoáy" bởi Nguyễn Văn A.', 'time' => '5 phút trước'],
    ['type' => 'Bình luận', 'content' => 'Bình luận mới về bài "Lợi ích của tập luyện thể lực."', 'time' => '15 phút trước'],
    ['type' => 'Nháp', 'content' => 'Lưu nháp bài viết "Phong trào tennis trẻ ở Việt Nam" bởi Trần Thị B.', 'time' => '1 giờ trước'],
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm bài viết, chuyên mục...">
                <i class="fas fa-search"></i>
            </div>
            <div class="user-info">
                <a href="#"><i class="fas fa-bell"></i><span class="badge">3</span></a>
                <span class="username">Xin chào, Admin!</span>
                <img src="img/admin_avatar.png" alt="Admin Avatar" class="avatar">
            </div>
        </div>
        <div class="dashboard-content-wrapper">
            <h2>Dashboard Tổng quan</h2>
            
            <div class="kpi-cards">
                <div class="kpi-card">
                    <div class="icon bg-blue"><i class="fas fa-file-alt"></i></div>
                    <div class="data">
                        <span class="number"><?php echo number_format($total_posts); ?></span>
                        <span class="label">Tổng Bài viết</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="icon bg-green"><i class="fas fa-check-circle"></i></div>
                    <div class="data">
                        <span class="number"><?php echo number_format($published_posts); ?></span>
                        <span class="label">Đã Xuất bản</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="icon bg-orange"><i class="fas fa-pencil-alt"></i></div>
                    <div class="data">
                        <span class="number"><?php echo number_format($draft_posts); ?></span>
                        <span class="label">Bài viết Nháp</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="icon bg-red"><i class="fas fa-eye"></i></div>
                    <div class="data">
                        <span class="number"><?php echo $weekly_views; ?></span>
                        <span class="label">Lượt xem Tuần</span>
                    </div>
                </div>
            </div>
            <div class="dashboard-rows">
                <div class="row-left">
                    <div class="widget chart-widget">
                        <h3>Lượt xem Bài viết (30 ngày gần nhất)</h3>
                        <canvas id="viewsChart"></canvas>
                    </div>
                    <div class="widget top-posts-widget">
                        <h3>Bài viết nổi bật nhất (Top 5)</h3>
                        <ul class="post-list">
                            <?php foreach ($top_posts as $post): ?>
                            <li>
                                <span><?php echo htmlspecialchars($post['title']); ?></span>
                                <span class="views"><?php echo $post['views']; ?> views</span>
                                <a href="admin_post_edit.php?id=..." class="edit-link"><i class="fas fa-edit"></i></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    </div>

                <div class="row-right">
                    <div class="widget activity-widget">
                        <h3>Hoạt động gần đây</h3>
                        <ul class="activity-list">
                            <?php foreach ($recent_activities as $activity): ?>
                            <li>
                                <span class="activity-type type-<?php echo strtolower(str_replace(' ', '-', $activity['type'])); ?>"><?php echo $activity['type']; ?></span>
                                <p><?php echo htmlspecialchars($activity['content']); ?></p>
                                <span class="activity-time"><?php echo $activity['time']; ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="widget comments-widget">
                        <h3>Bình luận mới (<?php echo $new_comments; ?>)</h3>
                        <div class="comment-summary">
                            <p>Đang chờ duyệt: **<?php echo $new_comments; ?>** bình luận.</p>
                            <p>Cần trả lời: **12** bình luận đã duyệt.</p>
                            <a href="admin_comments.php" class="btn btn-primary">Xem tất cả Bình luận <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    </div>
            </div>

        </div>
        </div>
    </div>

<script>
    // --- Script khởi tạo Biểu đồ Thống kê Lượt xem ---
    const ctx = document.getElementById('viewsChart').getContext('2d');
    const viewsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['1/11', '3/11', '5/11', '7/11', '9/11', '11/11'],
            datasets: [{
                label: 'Lượt xem (Views)',
                data: [350, 500, 420, 750, 680, 950], // Dữ liệu giả định
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.3, // Độ cong của đường
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.2)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Cho phép widget tự điều chỉnh kích thước
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

</body>
</html>