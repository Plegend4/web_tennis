<?php 
$page_title = "Thư viện Video - CLB Tennis DNT Việt Nam";

// Định nghĩa CSS riêng cho trang này
$page_specific_styles = "
    .video-page-section {
        padding-top: 100px; /* Offset cho Header cố định */
        padding-bottom: 4rem;
    }
    .video-page-section .section-title {
         text-align: left;
         padding-bottom: 0.5rem;
         margin-bottom: 2rem;
    }
    .video-page-section .section-title::after {
        left: 0;
        transform: none;
    }
    .video-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }
    .video-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(124, 179, 66, 0.4);
    }
    .video-embed {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
    }
    .video-embed iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
    .video-content {
        padding: 1.2rem;
    }
    .video-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--color-primary);
    }
";

include 'header.php'; 
?> 
    <main>
        <section class="video-page-section">
            <div class="container">
                <h1 class="section-title">Thư Viện Video</h1>
                
                <div class="video-gallery-grid">
                    
                    <?php
                    // Truy vấn dữ liệu video từ CSDL
                    $sql_videos = "SELECT title, youtube_id FROM videos ORDER BY upload_date DESC";
                    $result_videos = $conn->query($sql_videos);

                    if ($result_videos && $result_videos->num_rows > 0) {
                        while($video = $result_videos->fetch_assoc()) {
                            $youtube_url = "https://www.youtube.com/embed/" . htmlspecialchars($video['youtube_id']);
                            echo '
                            <div class="video-card">
                                <div class="video-embed">
                                    <iframe src="' . $youtube_url . '" title="' . htmlspecialchars($video['title']) . '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="video-content">
                                    <h3 class="video-title">' . htmlspecialchars($video['title']) . '</h3>
                                </div>
                            </div>';
                        }
                    } else {
                        echo "<p style='text-align: center; grid-column: 1 / -1;'>Hiện chưa có video nào. Vui lòng quay lại sau.</p>";
                    }
                    ?>
                    </div>

            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
