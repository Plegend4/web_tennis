    <footer>
        <div class="container">
            <div class="footer-main">
                <div class="footer-col about-col">
                    <div class="footer-logo">
                        <img src="img/logotn.png" alt="Logo DNT Việt Nam" class="site-logo footer-logo-img">
                        <div>
                            <div class="footer-logo-text">CLB Tennis</div>
                            <div class="footer-logo-sub">DNT Việt Nam</div>
                        </div>
                    </div>
                    <p class="footer-desc">Câu lạc bộ Tennis hàng đầu Việt Nam, kết nối đam mê và nâng tầm kỹ năng của bạn cùng cộng đồng yêu tennis chuyên nghiệp.</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon youtube"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-icon tiktok"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="social-icon telegram"><i class="fab fa-telegram-plane"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h3 class="footer-title">Liên kết nhanh</h3>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-chevron-right"></i> Trang chủ</a></li>
                        <li><a href="tin-tuc.php"><i class="fas fa-chevron-right"></i> Tin tức</a></li>
                        <li><a href="hoat-dong.php"><i class="fas fa-chevron-right"></i> Hoạt động</a></li>
                        <li><a href="index.php#lich-thi-dau"><i class="fas fa-chevron-right"></i> Lịch thi đấu</a></li>
                        <li><a href="index.php#gioi-thieu"><i class="fas fa-chevron-right"></i> Giới thiệu</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3 class="footer-title">Dịch vụ</h3>
                    <ul class="footer-links">
                        <?php
                        if (isset($conn)) {
                            $sql_services = "SELECT name, link FROM services ORDER BY id ASC";
                            $result_services = $conn->query($sql_services);

                            if ($result_services && $result_services->num_rows > 0) {
                                while($row = $result_services->fetch_assoc()) {
                                    $link = htmlspecialchars($row["link"]);
                                    $name = htmlspecialchars($row["name"]);
                                    echo '<li><a href="' . $link . '"><i class="fas fa-chevron-right"></i> ' . $name . '</a></li>';
                                }
                            } else {
                                echo '<li><a href="#"><i class="fas fa-chevron-right"></i> Đang cập nhật</a></li>';
                            }
                        }
                        ?>
                        </ul>
                </div>

                <div class="footer-col">
                    <h3 class="footer-title">Liên hệ</h3>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Số 123, Đường Trần Duy Hưng<br>Cầu Giấy, Hà Nội</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>+84 24 1234 5678</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@tennisdnt.vn</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Thứ 2 - CN: 6:00 - 22:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-partners">
                <h3 class="section-subtitle">ĐỐI TÁC VÀ NHÀ TÀI TRỢ CHÍNH THỨC</h3>
                <div class="partner-logos">
                    <?php
                    if (isset($conn)) {
                        $sql_sponsors = "SELECT name, logo_url FROM sponsors ORDER BY level DESC, name ASC LIMIT 4";
                        $result_sponsors = $conn->query($sql_sponsors);

                        if ($result_sponsors && $result_sponsors->num_rows > 0) {
                            while($row = $result_sponsors->fetch_assoc()) {
                                $raw_logo = $row["logo_url"];
                                $logo_url = safe_image_url($raw_logo);
                                $name = htmlspecialchars($row["name"]);
                                $logo_src = $logo_url ? $logo_url : 'img/partner-placeholder.png';
                                // Nếu là đường dẫn tương đối và file không tồn tại trên server, fallback sang img/logo.png
                                if ($logo_src && strpos($logo_src, 'http') !== 0 && strpos($logo_src, '/') !== 0) {
                                    $localPath = __DIR__ . '/' . $logo_src;
                                    if (!file_exists($localPath)) {
                                        // Nếu file không tồn tại, thử tìm với các đuôi ảnh phổ biến (.png, .jpg, .jpeg, .webp)
                                        $pathInfo = pathinfo($logo_src);
                                        $dir = ($pathInfo['dirname'] && $pathInfo['dirname'] !== '.') ? $pathInfo['dirname'] . '/' : '';
                                        $name = $pathInfo['filename'];
                                        $candidates = ['png','jpg','jpeg','webp'];
                                        $found = false;
                                        foreach ($candidates as $ext) {
                                            $tryLocal = __DIR__ . '/' . $dir . $name . '.' . $ext;
                                            if (file_exists($tryLocal)) {
                                                $logo_src = $dir . $name . '.' . $ext;
                                                $found = true;
                                                break;
                                            }
                                        }
                                        if (!$found) {
                                            $logo_src = 'img/logo.png';
                                        }
                                    }
                                }
                                // Force no filter inline as a hotfix in case other CSS or caching applies a grayscale filter
                                echo '<img src="' . htmlspecialchars($logo_src) . '" alt="' . $name . '" class="partner-logo" title="' . $name . '" style="filter: none !important;">';
                            }
                        } else {
                            // Demo placeholders khi chưa có logo
                            for ($i = 1; $i <= 4; $i++) {
                                echo '<div class="partner-logo-placeholder">Logo ' . $i . '</div>';
                            }
                        }
                    }
                    ?>
                    </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>© 2025 Tennis Club - DNT Việt Nam. All rights reserved.</p>
                    <div class="footer-bottom-links">
                        <a href="#">Chính sách bảo mật</a>
                        <span>|</span>
                        <a href="#">Điều khoản sử dụng</a>
                        <span>|</span>
                        <a href="#">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php
    // Đóng kết nối CSDL ở cuối trang
    if (isset($conn)) {
        $conn->close();
    }
    ?>

    <script>
        function toggleMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }
    </script>
    
    <?php
    // Dành cho các script tùy chỉnh của từng trang
    if (!empty($page_specific_scripts)) {
        echo $page_specific_scripts;
    }
    ?>
</body>
</html>
