<?php 
$page_title = "Hoạt Động - CLB Tennis DNT Việt Nam";

// Định nghĩa CSS riêng cho trang này
$page_specific_styles = "
    .activity-page-section {
        padding-top: 100px; /* Offset cho Header cố định */
        padding-bottom: 4rem;
    }
    .activity-page-section .section-title {
         text-align: left;
         padding-bottom: 0.5rem;
         margin-bottom: 2rem;
    }
    .activity-page-section .section-title::after {
        left: 0;
        transform: none;
    }
    /* Sử dụng lại style của news-card cho đồng bộ */
    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    .activity-card {
        display: block; /* Để thẻ a hoạt động như block */
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-decoration: none;
        color: var(--color-text-dark);
    }
    .activity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(124, 179, 66, 0.4);
    }
    .activity-image {
        width: 100%;
        height: 200px; /* Tăng chiều cao ảnh */
        background-size: cover;
        background-position: center;
        transition: transform 0.6s ease;
    }
    .activity-card:hover .activity-image {
        transform: scale(1.05);
    }
    .activity-content {
        padding: 1.2rem;
    }
    .activity-date {
        color: var(--color-accent);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    .activity-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.8rem;
        color: var(--color-primary);
    }
    .activity-desc {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }
";

include 'header.php'; 
?> 
    <main>
        <section class="activity-page-section">
            <div class="container">
                <h1 class="section-title">Hoạt Động & Sự Kiện</h1>
                
                <div class="activities-grid">
                    
                    <?php
                    // 1. TRUY VẤN CSDL
                    $sql_activities = "SELECT name, slug, description, image_url, activity_date FROM activities ORDER BY activity_date DESC";
                    $result_activities = $conn->query($sql_activities);

                    // 2. KIỂM TRA VÀ HIỂN THỊ DỮ LIỆU
                    if ($result_activities && $result_activities->num_rows > 0) {
                        while($activity = $result_activities->fetch_assoc()) {
                            $formatted_date = date("d/m/Y", strtotime($activity["activity_date"]));
                            // Liên kết đến trang chi tiết (sẽ tạo sau)
                            $detail_link = "chi-tiet-hoat-dong.php?slug=" . htmlspecialchars($activity["slug"]); 
                            
                            // Cắt ngắn mô tả để hiển thị
                            $short_desc = htmlspecialchars($activity['description']);
                            if (strlen($short_desc) > 120) {
                                $short_desc = substr($short_desc, 0, 120) . '...';
                            }

                            echo '
                            <a href="' . $detail_link . '" class="activity-card">
                                <div class="activity-image-wrapper">
                                    <img src="' . htmlspecialchars($activity['image_url']) . '" alt="' . htmlspecialchars($activity['name']) . '">
                                </div>
                                <div class="activity-content">
                                    <div class="activity-date">Ngày diễn ra: ' . $formatted_date . '</div>
                                    <h3 class="activity-title">' . htmlspecialchars($activity['name']) . '</h3>
                                    <p class="activity-desc">' . $short_desc . '</p>
                                </div>
                            </a>';
                        }
                    } else {
                        echo "<p style='text-align: center; grid-column: 1 / -1;'>Hiện tại không có hoạt động nào được lên lịch. Vui lòng quay lại sau.</p>";
                    }
                    ?>
                    </div>

            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>