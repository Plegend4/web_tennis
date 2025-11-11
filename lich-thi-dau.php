<?php 
include 'config.php';
$page_title = "Lịch Thi Đấu - CLB Tennis DNT Việt Nam";

// CSS riêng cho trang lịch thi đấu
$page_specific_styles = "
    .schedule-page-section {
        padding-top: 100px; /* Offset cho Header cố định */
        padding-bottom: 5rem;
        background-color: #f7f9fc; /* Thêm màu nền nhẹ */
    }
    .schedule-page-section .section-title {
         text-align: center; /* Căn giữa tiêu đề chính */
         margin-bottom: 3rem;
         font-size: 2.5rem;
         color: var(--color-dark);
    }
    .schedule-page-section .section-title::after {
        left: 50%; /* Căn giữa gạch chân */
        transform: translateX(-50%);
        width: 100px; /* Tăng độ rộng gạch chân */
    }
    .schedule-table {
        margin-bottom: 4rem;
        background-color: #fff;
        padding: 2rem;
        border-radius: 15px; /* Bo góc cho card chứa bảng */
        box-shadow: 0 10px 30px rgba(0,0,0,0.07);
    }
    .schedule-table h2 {
        font-size: 1.6rem; /* Điều chỉnh kích thước */
        color: var(--color-primary);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--color-primary-light); /* Thêm đường kẻ chân */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        /* box-shadow đã chuyển lên .schedule-table */
    }
    th, td {
        padding: 1.1rem 1.3rem; /* Tăng padding */
        text-align: left;
        border-bottom: 1px solid #f0f0f0; /* Làm mờ đường kẻ */
    }
    thead {
        /* Bỏ background ở thead để dùng cho th */
    }
    th {
        background-color: var(--color-primary); /* Nền màu chính */
        color: #fff; /* Chữ trắng */
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px; /* Tăng khoảng cách chữ */
    }
    /* Bo góc cho header của bảng */
    thead th:first-child {
        border-top-left-radius: 8px;
    }
    thead th:last-child {
        border-top-right-radius: 8px;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    tbody tr:hover {
        background-color: #f5f8ff; /* Đổi màu hover */
        transform: scale(1.01); /* Hiệu ứng phóng to nhẹ */
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .match-date {
        font-weight: 700; /* Đậm hơn */
        color: var(--color-accent);
    }
    .match-result {
        font-weight: 700;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        text-align: center;
        min-width: 80px;
        display: inline-block;
    }
    .match-result.win {
        color: #28a745; 
        background-color: rgba(40, 167, 69, 0.1);
    }
    .match-result.loss {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
    }
    .match-result.draw {
        color: #6c757d;
        background-color: rgba(108, 117, 125, 0.1);
    }
    .pagination {
        display: flex;
        justify-content: center;
        padding: 2rem 0;
        list-style: none;
    }
    .pagination a, .pagination span {
        color: var(--color-primary);
        padding: 0.8rem 1.2rem;
        margin: 0 0.3rem;
        border: 1px solid var(--color-primary-light);
        border-radius: 5px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .pagination a:hover {
        background-color: var(--color-primary-light);
        border-color: var(--color-primary);
    }
    .pagination .current-page {
        background-color: var(--color-primary);
        color: #fff;
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
    <section class="schedule-page-section">
        <div class="container">
            <h1 class="section-title">Lịch Thi Đấu & Kết Quả</h1>

            <!-- Lịch thi đấu sắp tới -->
            <div class="schedule-table">
                <h2>Lịch Thi Đấu Sắp Tới</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Ngày & Giờ</th>
                            <th>Giải đấu</th>
                            <th>Đối thủ</th>
                            <th>Địa điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_upcoming = "SELECT event_name, opponent, match_datetime, location FROM matches WHERE status = 'Upcoming' ORDER BY match_datetime ASC";
                        $result_upcoming = $conn->query($sql_upcoming);

                        if ($result_upcoming->num_rows > 0) {
                            while($row = $result_upcoming->fetch_assoc()) {
                                $formatted_datetime = date("d/m/Y H:i", strtotime($row["match_datetime"]));
                                echo '
                                <tr>
                                    <td class="match-date">' . $formatted_datetime . '</td>
                                    <td>' . htmlspecialchars($row["event_name"]) . '</td>
                                    <td>' . htmlspecialchars($row["opponent"]) . '</td>
                                    <td>' . htmlspecialchars($row["location"]) . '</td>
                                </tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" style="text-align: center;">Không có lịch thi đấu nào sắp tới.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Kết quả thi đấu -->
            <div class="schedule-table">
                <h2>Kết Quả Thi Đấu</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Giải đấu</th>
                            <th>Đối thủ</th>
                            <th>Kết quả</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_finished = "SELECT event_name, opponent, match_datetime, result FROM matches WHERE status = 'Finished' ORDER BY match_datetime DESC";
                        $result_finished = $conn->query($sql_finished);

                        if ($result_finished->num_rows > 0) {
                            while($row = $result_finished->fetch_assoc()) {
                                $formatted_date = date("d/m/Y", strtotime($row["match_datetime"]));
                                
                                // Thêm class CSS dựa trên kết quả
                                $result_text = htmlspecialchars($row["result"]);
                                $result_class = '';
                                if (stripos($result_text, 'Thắng') !== false) {
                                    $result_class = 'win';
                                } elseif (stripos($result_text, 'Thua') !== false) {
                                    $result_class = 'loss';
                                } elseif (stripos($result_text, 'Hòa') !== false) {
                                    $result_class = 'draw';
                                }

                                echo '
                                <tr>
                                    <td>' . $formatted_date . '</td>
                                    <td>' . htmlspecialchars($row["event_name"]) . '</td>
                                    <td>' . htmlspecialchars($row["opponent"]) . '</td>
                                    <td class="match-result ' . $result_class . '">' . $result_text . '</td>
                                </tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" style="text-align: center;">Chưa có kết quả thi đấu nào.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
