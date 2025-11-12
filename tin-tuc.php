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
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 3rem;
        padding-bottom: 2rem;
    }
    .pagination a, .pagination span {
        padding: 0.8rem 1.2rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        text-decoration: none;
        color: var(--color-primary);
        transition: all 0.3s;
        font-weight: 500;
    }
    .pagination a:hover {
        background: var(--color-accent);
        color: white;
        border-color: var(--color-accent);
    }
    .pagination .current-page {
        background: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
        font-weight: bold;
    }
    .pagination .disabled {
        color: #ccc;
        pointer-events: none;
        border-color: #eee;
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
                    // Cài đặt pagination
                    $per_page = 9; // 9 bài viết mỗi trang (3x3 grid)
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $current_page = max(1, $current_page); // Đảm bảo page >= 1

                    // Đếm tổng số bài viết
                    $sql_count = "SELECT COUNT(*) as total FROM news";
                    $result_count = $conn->query($sql_count);
                    $total_news = $result_count->fetch_assoc()['total'];
                    $total_pages = ceil($total_news / $per_page);

                    // Đảm bảo current_page không vượt quá total_pages
                    $current_page = min($current_page, $total_pages);

                    // Tính offset
                    $offset = ($current_page - 1) * $per_page;

                    // Truy vấn với LIMIT và OFFSET
                    $sql_news = "SELECT post_date, title, excerpt, image_url, slug FROM news ORDER BY post_date DESC LIMIT ? OFFSET ?";
                    $stmt = $conn->prepare($sql_news);
                    $stmt->bind_param("ii", $per_page, $offset);
                    $stmt->execute();
                    $result_news = $stmt->get_result();

                    if ($result_news->num_rows > 0) {
                        while($row = $result_news->fetch_assoc()) {
                            $formatted_date = date("d/m/Y", strtotime($row["post_date"]));
                            $detail_link = "chi-tiet-tin.php?slug=" . htmlspecialchars($row["slug"]);
                            $img = safe_image_url($row["image_url"]);
                            $img_src = $img ? $img : 'img/placeholder-news.jpg';

                            echo '
                            <a href="' . $detail_link . '" class="news-card">
                                <div class="news-image" style="background-image: url(\'' . htmlspecialchars($img_src) . '\');"></div>
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

                    $stmt->close();
                    ?>
                    </div>

                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php
                        // Previous button
                        if ($current_page > 1) {
                            echo '<a href="?page=' . ($current_page - 1) . '">&laquo; Trước</a>';
                        } else {
                            echo '<span class="disabled">&laquo; Trước</span>';
                        }

                        // Page numbers
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);

                        if ($start_page > 1) {
                            echo '<a href="?page=1">1</a>';
                            if ($start_page > 2) {
                                echo '<span>...</span>';
                            }
                        }

                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($i == $current_page) {
                                echo '<span class="current-page">' . $i . '</span>';
                            } else {
                                echo '<a href="?page=' . $i . '">' . $i . '</a>';
                            }
                        }

                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<span>...</span>';
                            }
                            echo '<a href="?page=' . $total_pages . '">' . $total_pages . '</a>';
                        }

                        // Next button
                        if ($current_page < $total_pages) {
                            echo '<a href="?page=' . ($current_page + 1) . '">Sau &raquo;</a>';
                        } else {
                            echo '<span class="disabled">Sau &raquo;</span>';
                        }
                        ?>
                    </div>
                    <?php endif; ?>

            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>