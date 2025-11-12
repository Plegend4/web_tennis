<?php 
$page_title = "DNT Việt Nam Tennis Club - Trang Chủ";
include 'header.php'; 
?>

    <main>
        <section class="hero" id="trang-chu" style="background-image: url('https://images.unsplash.com/photo-1543457199-52e6d6c6d2d3?w=1600');">
            <div class="hero-content-overlay">
                <h1>CHINH PHỤC Sân Bóng Bằng Bàn Tay Đỉnh Cao Cùng <span class="hero-highlight">Doanh Nghiệp Trẻ</span></h1>
                <p>Gia nhập CLB Tennis hàng đầu Việt Nam. Nơi đam mê được bồi dưỡng và kỹ thuật được nâng tầm.</p>
                <a href="#thanh-vien" class="btn-cta">ĐĂNG KÝ THÀNH VIÊN NGAY! <i class="fas fa-chevron-right"></i></a>
            </div>
        </section>

        <section class="marquee-section">
            <div class="marquee-title">
                <h3 class="section-subtitle">34 CLB & ĐỐI TÁC TRÊN TOÀN QUỐC</h3>
            </div>
            <div class="marquee">
                <div class="marquee-content">
                    <?php
                    // Lấy danh sách các tỉnh/thành phố từ CSDL
                    $sql_cities = "SELECT DISTINCT city FROM clubs WHERE is_active = 1 ORDER BY city";
                    $result_cities = $conn->query($sql_cities);
                    $cities = [];
                    if ($result_cities->num_rows > 0) {
                        while($row = $result_cities->fetch_assoc()) {
                            $cities[] = htmlspecialchars($row['city']);
                        }
                    } else {
                        // Fallback nếu không có CLB nào
                        $cities = ['Hà Nội', 'TP.HCM', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ'];
                    }

                    // Lặp lại mảng 2 lần để hiệu ứng marquee đẹp hơn
                    $marquee_cities = array_merge($cities, $cities);

                    foreach ($marquee_cities as $city) {
                        // try to render a club image if exists using multiple naming patterns;
                        // common files in repo include names like ha-noi.jpg, dak-lak.jpg, can-tho.jpg
                        $slug = slugify($city);
                        $foundImg = '';
                        $exts = ['jpg','png','jpeg','webp'];
                        // candidates: clb-{slug}, {slug}
                        $candidates = ['clb-' . $slug, $slug];
                        foreach ($candidates as $base) {
                            foreach ($exts as $ext) {
                                $try = 'img/' . $base . '.' . $ext;
                                if (file_exists(__DIR__ . '/' . $try)) {
                                    $foundImg = $try;
                                    break 2;
                                }
                            }
                        }

                        if ($foundImg) {
                            echo '<span class="province-placeholder"><img class="marquee-logo" src="' . htmlspecialchars($foundImg) . '" alt="' . htmlspecialchars($city) . '"></span>';
                        } else {
                            echo '<span class="province-placeholder">' . htmlspecialchars($city) . '</span>';
                        }
                    }
                    ?>
                </div>
            </div>
        </section>

        <section class="promo-banner">
            <div class="container banner-content">
                <p>🏆 **GIẢI VÔ ĐỊCH TENNIS MỞ RỘNG MÙA HÈ** Đã mở đăng ký! </p>
                <a href="#" class="btn-banner-cta">ĐĂNG KÝ NGAY <i class="fas fa-chevron-right"></i></a>
            </div>
        </section>

        <section id="tin-tuc">
            <div class="container">
                <h2 class="section-title">Tin tức nổi bật</h2>
                <div class="news-slider-container">
                    <div class="news-slider-wrapper">
                        
                        <?php
                        // Sửa tên cột ngày tháng thành 'post_date'
                        $sql_news = "SELECT post_date, title, excerpt, image_url, slug FROM news ORDER BY post_date DESC LIMIT 5";
                        $result_news = $conn->query($sql_news);

                        if ($result_news->num_rows > 0) {
                            while($row = $result_news->fetch_assoc()) {
                                $formatted_date = date("d/m/Y", strtotime($row["post_date"]));
                                $detail_link = "chi-tiet-tin.php?slug=" . htmlspecialchars($row["slug"]);
                                // compute safe image URL before output
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
                            echo "<p style='padding: 0 10px; text-align: center;'>Chưa có tin tức nổi bật nào được đăng.</p>";
                        }
                        ?>
                        </div>
                </div>
                <div class="view-all">
                    <a href="tin-tuc.php" class="btn-secondary">XEM TẤT CẢ TIN TỨC <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <section id="hoat-dong">
            <div class="container">
                <h2 class="section-title">Hoạt động nổi bật</h2>
                <div class="activities-grid">
                    <?php
                    // Lấy 4 hoạt động mới nhất
                    $sql_activities = "SELECT name, slug, image_url FROM activities ORDER BY activity_date DESC LIMIT 4";
                    $result_activities = $conn->query($sql_activities);

                    if ($result_activities && $result_activities->num_rows > 0) {
                        while($row = $result_activities->fetch_assoc()) {
                            $detail_link = "chi-tiet-hoat-dong.php?slug=" . htmlspecialchars($row["slug"]);
                            $actImg = safe_image_url($row['image_url']);
                            $act_src = $actImg ? $actImg : 'img/placeholder-activity.jpg';
                            echo '
                            <a href="' . $detail_link . '" class="activity-card">
                                <div class="activity-image-wrapper">
                                    <img src="' . htmlspecialchars($act_src) . '" alt="' . htmlspecialchars($row['name']) . '">
                                </div>
                                <div class="activity-title">' . htmlspecialchars($row['name']) . '</div>
                            </a>';
                        }
                    } else {
                        echo "<p>Chưa có hoạt động nào.</p>";
                    }
                    ?>
                </div>
                 <div class="view-all">
                    <a href="hoat-dong.php" class="btn-secondary">XEM TẤT CẢ HOẠT ĐỘNG <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <section id="video">
            <div class="container">
                <h2 class="section-title">Video nổi bật</h2>
                <div class="video-container">
                    <?php
                    // Lấy video mới nhất
                    $sql_video = "SELECT title, youtube_id FROM videos ORDER BY upload_date DESC LIMIT 1"; 
                    $result_video = $conn->query($sql_video);

                    if ($result_video->num_rows > 0) {
                        $row_video = $result_video->fetch_assoc();
                        $youtube_id = htmlspecialchars($row_video['youtube_id']);
                        echo '<iframe src="https://www.youtube.com/embed/' . $youtube_id . '" allowfullscreen title="' . htmlspecialchars($row_video['title']) . '"></iframe>';
                    } else {
                        // Iframe dự phòng nếu CSDL rỗng
                        echo '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen title="Video dự phòng"></iframe>';
                    }
                    ?>
                    </div>
            </div>
        </section>

        <section id="lich-thi-dau">
            <div class="container">
                <h2 class="section-title">Lịch thi đấu sắp tới</h2>
                <div class="schedule-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Giờ</th>
                                <th>Địa điểm</th>
                                <th>Đối thủ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sửa tên cột thành 'match_datetime'
                            $sql_schedule = "SELECT match_datetime, location, opponent FROM matches WHERE status = 'Upcoming' ORDER BY match_datetime ASC LIMIT 4";
                            $result_schedule = $conn->query($sql_schedule);
                            
                            if ($result_schedule->num_rows > 0) {
                                while($row = $result_schedule->fetch_assoc()) {
                                    // Lấy Ngày và Giờ từ cột DATETIME
                                    $formatted_date = date("d/m/Y", strtotime($row["match_datetime"]));
                                    $formatted_time = date("H:i", strtotime($row["match_datetime"])); 
                                    
                                    echo '
                                    <tr>
                                        <td class="match-date">' . $formatted_date . '</td>
                                        <td>' . $formatted_time . '</td>
                                        <td>' . htmlspecialchars($row["location"]) . '</td>
                                        <td>' . htmlspecialchars($row["opponent"]) . '</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" style="text-align: center;">Chưa có lịch thi đấu sắp tới.</td></tr>';
                            }
                            ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <?php 
// Script dành riêng cho trang chủ để cuộn mượt đến các anchor
$page_specific_scripts = "
<script>
    document.querySelectorAll('a[href^=\"#\"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                // Đóng menu mobile nếu đang mở
                document.getElementById('navMenu').classList.remove('active');
            }
        });
    });
</script>
";

include 'footer.php'; 
?>