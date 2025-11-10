<?php
// Bao gồm file config để kết nối CSDL và sử dụng cho mọi trang
include_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'CLB Tennis DNT Việt Nam'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> 
    <?php 
    // Dành cho các CSS tùy chỉnh của từng trang
    if (!empty($page_specific_styles)) {
        echo "<style>{$page_specific_styles}</style>";
    }
    ?>
</head>
<body>

    <header>
        <nav class="container">
            <div class="logo">
                <img src="img/logotn.png" alt="Logo DNT Việt Nam" class="site-logo">
                <div>
                    <div class="logo-text">Tennis Club</div>
                    <div class="logo-sub">DNT Việt Nam</div>
                </div>
            </div>
            
            <div class="mobile-menu-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="tin-tuc.php">Tin tức</a></li>
                <li><a href="hoat-dong.php">Hoạt động</a></li>
                <li><a href="video.php">Video hay</a></li>
                <li><a href="index.php#lich-thi-dau">Lịch thi đấu</a></li>
                <li class="dropdown">
                    <a href="#">34 CLB thành viên <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content dropdown-scrollable">
                        <?php
                        if (isset($conn)) {
                            $sql_clubs = "SELECT name, city FROM clubs ORDER BY city ASC, name ASC";
                            $result_clubs = $conn->query($sql_clubs);

                            if ($result_clubs && $result_clubs->num_rows > 0) {
                                while($row = $result_clubs->fetch_assoc()) {
                                    // Liên kết đến các anchor trên trang chủ
                                    $club_link = "index.php#clb-" . urlencode($row["city"]);
                                    echo '<a href="' . $club_link . '">' . htmlspecialchars($row["name"]) . ' (' . htmlspecialchars($row["city"]) . ')</a>';
                                }
                            } else {
                                echo '<a href="#">Không có CLB thành viên</a>';
                            }
                        }
                        ?>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Thêm <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="index.php#gioi-thieu">Giới thiệu</a>
                        <a href="index.php#thanh-vien">Thành viên</a>
                        <a href="index.php#lien-he">Liên hệ</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header> 
