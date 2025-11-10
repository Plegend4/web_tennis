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
                    // Mảng dữ liệu video giả lập. Sau này sẽ thay bằng truy vấn CSDL
                    $videos = [
                        [
                            "title" => "Highlight trận chung kết giải A",
                            "youtube_url" => "https://www.youtube.com/embed/dQw4w9WgXcQ" // Placeholder URL
                        ],
                        [
                            "title" => "Kỹ thuật backhand nâng cao",
                            "youtube_url" => "https://www.youtube.com/embed/oHg5SJYRHA0" // Placeholder URL
                        ],
                        [
                            "title" => "Bài tập thể lực cho tay vợt",
                            "youtube_url" => "https://www.youtube.com/embed/a3Z7zEc7AXQ" // Placeholder URL
                        ],
                        [
                            "title" => "Phỏng vấn vận động viên sau trận đấu",
                            "youtube_url" => "https://www.youtube.com/embed/8-m_241XpOA" // Placeholder URL
                        ],
                         [
                            "title" => "Những pha bóng đẹp nhất tuần",
                            "youtube_url" => "https://www.youtube.com/embed/QH2-TGUlwu4" // Placeholder URL
                        ],
                         [
                            "title" => "Hướng dẫn giao bóng xoáy",
                            "youtube_url" => "https://www.youtube.com/embed/Y9-x972-g_o" // Placeholder URL
                        ]
                    ];

                    if (!empty($videos)) {
                        foreach($videos as $video) {
                            echo '
                            <div class="video-card">
                                <div class="video-embed">
                                    <iframe src="' . htmlspecialchars($video['youtube_url']) . '" title="' . htmlspecialchars($video['title']) . '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
