
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_tennis_club
DROP DATABASE IF EXISTS `db_tennis_club`;
CREATE DATABASE IF NOT EXISTS `db_tennis_club` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_tennis_club`;

-- Dumping structure for table db_tennis_club.clubs
DROP TABLE IF EXISTS `clubs`;
CREATE TABLE IF NOT EXISTS `clubs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên CLB thành viên',
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tỉnh/Thành phố',
  `contact_person` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người đại diện',
  `founded_year` year DEFAULT NULL COMMENT 'Năm thành lập',
	`image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh CLB',
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tennis_club.clubs: ~10 rows (approximately)
INSERT INTO `clubs` (`id`, `name`, `city`, `contact_person`, `founded_year`, `image_url`, `is_active`) VALUES
	(1, 'CLB Tennis Hà Nội', 'Hà Nội', 'Nguyễn Văn A', NULL, 'img/clb-ha-noi.png', 1),
	(2, 'CLB Tennis Hồ Chí Minh', 'Hồ Chí Minh', 'Trần Thị B', NULL, 'img/clb-ho-chi-minh.png', 1),
	(3, 'CLB Tennis Đà Nẵng', 'Đà Nẵng', 'Lê Văn C', NULL, 'img/clb-da-nang.png', 1),
	(4, 'CLB Tennis Hải Phòng', 'Hải Phòng', 'Phạm Thị D', NULL, 'img/clb-hai-phong.png', 1),
	(5, 'CLB Tennis Cần Thơ', 'Cần Thơ', 'Hoàng Văn E', NULL, 'img/clb-can-tho.png', 1),
	(6, 'CLB Tennis Vĩnh Phúc', 'Vĩnh Phúc', 'Đỗ Thị F', NULL, 'img/clb-vinh-phuc.png', 1),
	(7, 'CLB Tennis Đồng Nai', 'Đồng Nai', 'Bùi Văn G', NULL, 'img/clb-dong-nai.png', 1),
	(8, 'CLB Tennis Bình Dương', 'Bình Dương', 'Võ Thị H', NULL, 'img/clb-binh-duong.png', 1),
	(9, 'CLB Tennis Nghệ An', 'Nghệ An', 'Cao Văn I', NULL, 'img/clb-nghe-an.png', 1),
	(10, 'CLB Tennis Huế', 'Thừa Thiên Huế', 'Mai Thị K', NULL, 'img/clb-thua-thien-hue.png', 1);

-- Dumping structure for table db_tennis_club.matches
DROP TABLE IF EXISTS `matches`;
CREATE TABLE IF NOT EXISTS `matches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên giải đấu/sự kiện',
  `opponent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đối thủ',
  `match_datetime` datetime NOT NULL COMMENT 'Ngày và Giờ thi đấu',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa điểm sân thi đấu',
  `result` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kết quả (Ví dụ: Thắng 3-0, Thua 1-3)',
  `status` enum('Upcoming','Finished','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Upcoming',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tennis_club.matches: ~10 rows (approximately)
INSERT INTO `matches` (`id`, `event_name`, `opponent`, `match_datetime`, `location`, `result`, `status`) VALUES
	(1, 'Giải Vô Địch CLB Mùa Xuân', 'CLB Tennis Hà Nội Young', '2025-11-20 14:00:00', 'Sân Tennis Mỹ Đình, Hà Nội', NULL, 'Upcoming'),
	(2, 'Giao Hữu Doanh Nhân Miền Nam', 'CLB Tennis Sài Gòn Gold', '2025-11-25 09:30:00', 'Sân Tennis Quốc Gia, Hà Nội', NULL, 'Upcoming'),
	(3, 'Giải Tennis Toàn Quốc', 'CLB Tennis Đà Nẵng Aces', '2025-12-01 16:00:00', 'Sân Tennis Cầu Giấy, Hà Nội', NULL, 'Upcoming'),
	(4, 'Trận Đấu Từ Thiện', 'CLB Tennis Hải Phòng Eagles', '2025-12-10 10:00:00', 'Sân Tennis Văn Minh', NULL, 'Upcoming'),
	(5, 'Vòng Loại Mùa Hè', 'CLB Tennis Bình Dương Pro', '2025-12-15 15:30:00', 'Sân Tennis Quân Khu 7, TP.HCM', NULL, 'Upcoming'),
	(6, 'Giải Mở Rộng Tháng 10', 'CLB Tennis Vĩnh Phúc', '2025-10-01 14:30:00', 'Sân Tennis Vĩnh Phúc', 'Thắng 3-1', 'Finished'),
	(7, 'Cúp Đồng Đội', 'CLB Tennis Doanh Nhân', '2025-09-20 19:00:00', 'Sân Tennis Thủ Đức, TP.HCM', 'Thua 2-3', 'Finished'),
	(8, 'Vô Địch Đơn Nam', 'CLB Tennis Hàng Không', '2025-09-15 08:00:00', 'Sân Tennis Hàng Không', 'Thắng 3-0', 'Finished'),
	(9, 'Giao hữu Quốc Tế', 'Đội Tuyển Singapore', '2025-09-10 13:00:00', 'Sân Tennis Quốc Tế', 'Hòa 2-2', 'Finished'),
	(10, 'Giải Đôi Nam Nữ', 'CLB Tennis Quận 3', '2025-08-30 17:00:00', 'Sân Tennis Gia Định', 'Thua 1-3', 'Finished');

-- Dumping structure for table db_tennis_club.news
DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề bài viết',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL thân thiện',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh đại diện',
  `excerpt` text COLLATE utf8mb4_unicode_ci COMMENT 'Tóm tắt bài viết',
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung chi tiết',
  `post_date` datetime NOT NULL COMMENT 'Ngày và Giờ đăng bài',
  `is_featured` tinyint(1) DEFAULT '0' COMMENT 'Có phải tin nổi bật không',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tennis_club.news: ~10 rows (approximately)
INSERT INTO `news` (`id`, `title`, `slug`, `image_url`, `excerpt`, `content`, `post_date`, `is_featured`) VALUES
	(1, 'Giải Vô Địch Mùa Thu kết thúc thành công', 'giai-vo-dich-mua-thu', 'https://images.unsplash.com/photo-1540938478149-c13630f9a265?w=600', 'CLB DNT đã xuất sắc giành 3/5 hạng mục thi đấu. Đọc thêm về kết quả chi tiết!', 'Nội dung chi tiết về các trận đấu và vận động viên thắng giải...', '2025-11-09 10:00:00', 1),
	(2, 'Khai giảng khóa đào tạo nâng cao', 'khoa-dao-tao-nang-cao', 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=600', 'Khóa học chuyên sâu dành cho các VĐV muốn cải thiện kỹ thuật giao bóng và volleys.', 'Thông tin chi tiết về giáo trình và HLV...', '2025-11-05 14:30:00', 0),
	(3, 'Giao lưu CLB Tennis TP.HCM', 'giao-luu-tphcm', 'https://images.unsplash.com/photo-1517462002302-31015ed0d87c?w=600', 'Chuyến giao lưu đầy sôi nổi và hữu ích giữa CLB DNT và CLB thành viên phía Nam.', 'Hình ảnh và tổng hợp các hoạt động giao lưu...', '2025-10-30 08:00:00', 1),
	(4, 'Phân tích chuyên sâu kỹ thuật Forehand', 'phan-tich-forehand', 'https://images.unsplash.com/photo-1628172824677-2f3424d85202?w=600', 'Bí quyết Forehand uy lực và chính xác của các tay vợt hàng đầu thế giới.', 'Hướng dẫn từng bước để có cú Forehand hoàn hảo...', '2025-10-25 11:00:00', 0),
	(5, 'Thông báo Tuyển thành viên mới 2026', 'tuyen-thanh-vien-2026', 'https://images.unsplash.com/photo-1616766099516-72877a5e8f47?w=600', 'CLB đang mở rộng tuyển thành viên mới với các chương trình đào tạo chuyên nghiệp.', 'Các yêu cầu và quyền lợi khi trở thành thành viên...', '2025-10-20 16:00:00', 1),
	(6, 'Kỷ niệm 5 năm thành lập CLB DNT', 'ky-niem-5-nam', 'https://images.unsplash.com/photo-1577977468165-27a3c301402d?w=600', 'Buổi tiệc kỷ niệm ấm cúng với sự tham gia của các thành viên sáng lập và đối tác.', 'Tổng kết chặng đường 5 năm phát triển...', '2025-10-15 19:30:00', 0),
	(7, 'Bí quyết duy trì thể lực', 'bi-quyet-the-luc', 'https://images.unsplash.com/photo-1588722485572-c55490710636?w=600', 'Các bài tập và chế độ dinh dưỡng cần thiết được chuyên gia chia sẻ.', 'Chế độ luyện tập và dinh dưỡng khoa học...', '2025-10-10 09:00:00', 0),
	(8, 'Cập nhật luật thi đấu mới nhất', 'cap-nhat-luat-moi', 'https://images.unsplash.com/photo-1580220268501-729226f30d07?w=600', 'Các thay đổi quan trọng trong luật thi đấu bắt đầu từ quý 4 năm nay.', 'Các điều khoản luật mới và ý nghĩa của chúng...', '2025-10-05 13:00:00', 0),
	(9, 'Phỏng vấn: Hành trình nhà vô địch', 'phong-van-vdv', 'https://images.unsplash.com/photo-1587372332675-9e65691062b8?w=600', 'Gặp gỡ tay vợt trẻ tiềm năng nhất của CLB và lắng nghe chia sẻ của anh ấy.', 'Bài phỏng vấn độc quyền...', '2025-09-30 14:00:00', 0),
	(10, 'Thông báo bảo trì sân tập', 'bao-tri-san', 'https://images.unsplash.com/photo-1575034639535-c3f2b2b10287?w=600', 'Hệ thống sân tập sẽ tạm dừng hoạt động 2 ngày để nâng cấp cơ sở vật chất.', 'Chi tiết về thời gian bảo trì và các sân thay thế...', '2025-09-25 18:00:00', 0);

-- Dumping structure for table db_tennis_club.services
DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên dịch vụ',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn liên kết',
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Font Awesome icon class',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tennis_club.services: ~10 rows (approximately)
INSERT INTO `services` (`id`, `name`, `link`, `icon`) VALUES
	(1, 'Đào tạo Tennis Cơ bản', '#', 'fa-graduation-cap'),
	(2, 'Đào tạo Tennis Nâng cao', '#', 'fa-trophy'),
	(3, 'Thuê Sân Thi đấu', '#', 'fa-tennis-ball'),
	(4, 'Huấn luyện Cá nhân 1-1', '#', 'fa-user-check'),
	(5, 'Tổ chức Giải đấu', '#', 'fa-medal'),
	(6, 'Cửa hàng Dụng cụ', '#', 'fa-store'),
	(7, 'Tư vấn Dinh dưỡng Thể thao', '#', 'fa-leaf'),
	(8, 'Phục hồi Chấn thương', '#', 'fa-heartbeat'),
	(9, 'Dịch vụ căng dây Vợt', '#', 'fa-cogs'),
	(10, 'Khóa học Tennis Trẻ em', '#', 'fa-child');

-- Dumping structure for table db_tennis_club.sponsors
DROP TABLE IF EXISTS `sponsors`;
CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhà tài trợ',
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn đến logo',
  `level` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cấp độ tài trợ (Kim Cương, Vàng, Bạc)',
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Website của nhà tài trợ',
  `contact_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tennis_club.sponsors: ~5 rows (approximately)
INSERT INTO `sponsors` (`id`, `name`, `logo_url`, `level`, `website`, `contact_email`) VALUES
	(1, 'XTL Energy', 'img/logo_xtlenergy.png', 'Bạch Kim', 'http://xtlenergy.com', NULL),
	(2, 'Thánh Gióng - Máy Tính Việt Nam', 'img/logo_thanhgiong.png', 'Bạch Kim', 'https://www.thanhgiong.com.vn/en/', NULL),
	(3, 'UP Việt Nam', 'img/logo_upvietnam.png', 'Bạch Kim', 'https://web.facebook.com/UpMvalueforu/?_rdc=1&_rdr#', NULL),
	(4, 'Sông Trà', 'img/logo_songtra.png', 'Bạch Kim', 'http://songtra.vn', NULL),
	(5, 'G Group', 'img/logo_ggroup.png', 'Bạch Kim', 'http://ggroup.vn', NULL);

-- Dumping structure for table db_tennis_club.videos
DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề video',
  `youtube_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã ID YouTube',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả ngắn',
  `upload_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_tennis_club.videos: ~10 rows (approximately)
INSERT INTO `videos` (`id`, `title`, `youtube_id`, `description`, `upload_date`) VALUES
	(1, 'Highlight: Chung kết Giải Tennis DNT 2025', 'dQw4w9WgXcQ', 'Tổng hợp những khoảnh khắc đẹp nhất từ trận chung kết kịch tính.', '2025-11-01'),
	(2, 'Bài tập bổ trợ giúp tăng lực giao bóng', 'y6J81NfD6lQ', 'Hướng dẫn chi tiết các bài tập thể lực chuyên biệt cho tennis.', '2025-10-25'),
	(3, 'Phân tích cú Volley của Roger Federer', 'g9P7q1r8s0t', 'Chuyên gia phân tích kỹ thuật Volley cơ bản và nâng cao.', '2025-10-20'),
	(4, 'Kỹ thuật Backhand 2 tay cơ bản', 'a1b2c3d4e5f', 'Video hướng dẫn chi tiết cho người mới bắt đầu.', '2025-10-15'),
	(5, 'Hậu trường buổi chụp ảnh CLB', 'z9x8c7v6b5n', 'Các hoạt động vui nhộn trong buổi chụp ảnh thường niên.', '2025-10-10'),
	(6, 'Bài học số 1: Cách cầm vợt đúng', 'm1n2b3v4c5x', 'Video đầu tiên trong series đào tạo tennis cơ bản.', '2025-10-05'),
	(7, 'Giao lưu cùng CLB Doanh Nhân Sài Gòn', 'qwer4567tyu', 'Tổng hợp video các trận giao hữu thân mật.', '2025-09-30'),
	(8, 'Top 5 cú Smash đẹp nhất năm', 'lmnp0987qwe', 'Tuyển chọn những cú Smash mạnh mẽ và đẹp mắt.', '2025-09-25'),
	(9, 'Chế độ ăn uống của vận động viên tennis', 'asdfghjkl12', 'Bí quyết dinh dưỡng để duy trì phong độ cao.', '2025-09-20'),
	(10, 'Tour tham quan cơ sở vật chất mới', 'poiu9876tre', 'Giới thiệu các sân tập và phòng chức năng mới của CLB.', '2025-09-15');

-- --------------------------------------------------------
--
-- Cấu trúc bảng cho `activities`
-- Bảng này sẽ lưu trữ các hoạt động nổi bật của CLB.
--
-- --------------------------------------------------------

CREATE TABLE `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Chèn dữ liệu mẫu cho bảng `activities`
--
-- --------------------------------------------------------

INSERT INTO `activities` (`name`, `slug`, `description`, `image_url`, `activity_date`) VALUES
('Buổi tập huấn cuối tuần', 'buoi-tap-huan-cuoi-tuan', 'Đây là mô tả chi tiết về buổi tập huấn kỹ năng tennis vào cuối tuần. Mọi người sẽ được hướng dẫn bởi các huấn luyện viên chuyên nghiệp, tập trung vào cải thiện kỹ thuật giao bóng, trả bóng và chiến thuật thi đấu. Buổi tập huấn phù hợp cho mọi trình độ.', 'https://images.unsplash.com/photo-1554142522-052936535f34?w=500&q=80', NOW() + INTERVAL 7 DAY),
('Giao lưu gặp mặt thành viên', 'giao-luu-gap-mat-thanh-vien', 'Buổi gặp mặt thân mật giữa các thành viên CLB để giao lưu, chia sẻ kinh nghiệm và thắt chặt tình đoàn kết. Sẽ có tiệc nhẹ và các trò chơi vui nhộn. Đây là cơ hội tuyệt vời để kết nối với những người cùng đam mê.', 'https://images.unsplash.com/photo-1529565278326-c39356d41399?w=500&q=80', NOW() + INTERVAL 14 DAY),
('Thi đấu giao hữu', 'thi-dau-giao-huu', 'Giải đấu giao hữu hàng tháng nhằm cọ xát và nâng cao trình độ. Các tay vợt sẽ được chia cặp thi đấu theo thể thức loại trực tiếp. Giải thưởng hấp dẫn đang chờ đón các nhà vô địch.', 'https://images.unsplash.com/photo-1594464495848-9235b7a4f358?w=500&q=80', NOW() + INTERVAL 21 DAY),
('Lớp học cho trẻ em', 'lop-hoc-cho-tre-em', 'CLB mở các lớp học tennis cho các bé từ 6-12 tuổi, giúp các em làm quen với bộ môn và rèn luyện sức khỏe. Khóa học được thiết kế vui nhộn, an toàn và hiệu quả, do các HLV có kinh nghiệm giảng dạy.', 'https://images.unsplash.com/photo-1519414442633-94ce7529f278?w=500&q=80', NOW() + INTERVAL 1 MONTH);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;