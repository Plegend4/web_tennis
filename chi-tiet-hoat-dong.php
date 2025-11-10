<?php 
$page_title = "Chi Tiết Hoạt Động | CLB Tennis DNT Việt Nam"; 

// Định nghĩa CSS riêng cho trang này (tương tự trang chi tiết tin)
$page_specific_styles = "
    .detail-page-section {
        padding-top: 100px; /* Offset cho Header cố định */
        padding-bottom: 4rem;
    }
    .article-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .article-header h1 {
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
        color: var(--color-primary);
    }
    .article-meta {
        color: #666;
        font-size: 0.9rem;
    }
    .article-meta .date {
        color: var(--color-accent);
        font-weight: 600;
    }
    .article-image {
        width: 100%;
        height: 400px;
        background-size: cover;
        background-position: center;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .article-content {
        line-height: 1.8;
        font-size: 1rem;
        color: var(--color-text-dark);
        max-width: 900px;
        margin: 0 auto;
    }
    .article-content p {
        margin-bottom: 1.5rem;
    }
    .error-message {
        text-align: center;
        padding: 50px;
    }
";

include 'header.php'; 

$activity = null;

// 1. KIỂM TRA THAM SỐ SLUG TỪ URL
if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $_GET['slug'];
    
    // 2. TRUY VẤN CSDL THEO SLUG TỪ BẢNG 'activities'
    $stmt = $conn->prepare("SELECT name, description, image_url, activity_date FROM activities WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $activity = $result->fetch_assoc();
    }
    $stmt->close();
}
?> 
    <main>
        <section class="detail-page-section">
            <div class="container">
                
                <?php if ($activity): 
                    $formatted_date = date("d/m/Y", strtotime($activity["activity_date"]));
                ?>
                    <script>
                        // Cập nhật tiêu đề trang động
                        document.title = "<?php echo htmlspecialchars($activity['name']); ?> | CLB Tennis DNT";
                    </script>

                    <div class="article-header">
                        <div class="article-meta">Ngày diễn ra: <span class="date"><?php echo $formatted_date; ?></span></div>
                        <h1><?php echo htmlspecialchars($activity['name']); ?></h1>
                    </div>
                    
                    <div class="article-image">
                        <img src="<?php echo htmlspecialchars($activity['image_url']); ?>" alt="<?php echo htmlspecialchars($activity['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    </div>
                    
                    <div class="article-content">
                        <?php echo nl2br(htmlspecialchars($activity['description'])); ?>
                        
                        <p style="margin-top: 3rem; text-align: center;">
                            <a href="hoat-dong.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách hoạt động</a>
                        </p>
                    </div>

                <?php else: ?>
                    <div class="error-message">
                        <h2 class="section-title">Hoạt Động Không Tồn Tại</h2>
                        <p>Xin lỗi, hoạt động bạn đang tìm không còn tồn tại hoặc đã bị gỡ bỏ.</p>
                        <a href="hoat-dong.php" class="btn-secondary" style="margin-top: 20px;">Xem tất cả hoạt động <i class="fas fa-arrow-right"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
