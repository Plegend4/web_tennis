<?php 
$page_title = "Tin Tức CLB Tennis DNT Việt Nam";

// Định nghĩa CSS riêng cho trang này
$page_specific_styles = "
    .news-page-section {
        padding-top: 100px; /* Offset cho Header cố định */
        padding-bottom: 4rem;
    }
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    .news-page-section .section-title {
         text-align: left;
         padding-bottom: 0.5rem;
         margin-bottom: 2rem;
    }
    .news-page-section .section-title::after {
        left: 0;
        transform: none;
    }
";

include 'header.php'; 
?> 
    <main>
        <section class="news-page-section">
            <div class="container">
                <h1 class="section-title">Tin Tức & Sự Kiện Mới Nhất</h1>
                
                <div class="news-grid">
                    
                    <?php
                    // Truy vấn TẤT CẢ dữ liệu, sắp xếp theo ngày mới nhất
                    $sql_all_news = "SELECT post_date, title, excerpt, image_url, slug FROM news ORDER BY post_date DESC";
                    $result_all_news = $conn->query($sql_all_news);

                    if ($result_all_news->num_rows > 0) {
                        while($row = $result_all_news->fetch_assoc()) {
                            $formatted_date = date("d/m/Y", strtotime($row["post_date"]));
                            // Tạo liên kết chi tiết (Ví dụ: chi-tiet.php?slug=...)
                            $detail_link = "chi-tiet-tin.php?slug=" . htmlspecialchars($row["slug"]); 
                            
                            echo '
                            <a href="' . $detail_link . '" class="news-card">
                                <div class="news-image" style="background-image: url(\'' . htmlspecialchars($row["image_url"]) . '\');"></div>
                                <div class="news-content">
                                    <div class="news-date">' . $formatted_date . '</div>
                                    <h3 class="news-title">' . htmlspecialchars($row["title"]) . '</h3>
                                    <p class="news-excerpt">' . htmlspecialchars($row["excerpt"]) . '</p>
                                </div>
                            </a>';
                        }
                    } else {
                        echo "<p style='text-align: center; width: 100%;'>Hiện tại không có bài viết tin tức nào.</p>";
                    }
                    ?>
                    </div>

            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>