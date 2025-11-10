<?php 
$page_title = "Chi Tiết Tin Tức | CLB Tennis DNT Việt Nam"; 

// Định nghĩa CSS riêng cho trang này
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

$article = null;

// 1. KIỂM TRA THAM SỐ SLUG TỪ URL
if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $_GET['slug'];
    
    // 2. TRUY VẤN CSDL THEO SLUG
    // Sử dụng Prepared Statement để tránh lỗi SQL Injection
    $stmt = $conn->prepare("SELECT title, post_date, image_url, content FROM news WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
    }
    $stmt->close();
}
?> 
    <main>
        <section class="detail-page-section">
            <div class="container">
                
                <?php if ($article): 
                    $formatted_date = date("d/m/Y H:i", strtotime($article["post_date"]));
                ?>
                    <script>
                        // Cập nhật tiêu đề trang động
                        document.title = "<?php echo htmlspecialchars($article['title']); ?> | CLB Tennis DNT";
                    </script>

                    <div class="article-header">
                        <div class="article-meta">Đã đăng bởi CLB DNT vào <span class="date"><?php echo $formatted_date; ?></span></div>
                        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
                    </div>
                    
                    <div class="article-image" style="background-image: url('<?php echo htmlspecialchars($article['image_url']); ?>');"></div>
                    
                    <div class="article-content">
                        <?php echo nl2br($article['content']); /* Cân nhắc dùng HTML Purifier để bảo mật hơn */ ?>
                        
                        <p style="margin-top: 3rem;">**--- Hết bài viết ---**</p>
                    </div>

                <?php else: ?>
                    <div class="error-message">
                        <h2 class="section-title">Tin Tức Không Tồn Tại</h2>
                        <p>Xin lỗi, bài viết bạn đang tìm không còn tồn tại hoặc đã bị gỡ bỏ.</p>
                        <a href="tin-tuc.php" class="btn-secondary" style="margin-top: 20px;">Quay lại trang Tin tức <i class="fas fa-arrow-right"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>