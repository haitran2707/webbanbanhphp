<?php
include 'connect.php';

$maSP = isset($_GET['masp']) ? intval($_GET['masp']) : 1; // Nhận MaSP từ request
$limit = 5;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 5; // Offset từ request

// Truy vấn danh sách đánh giá của sản phẩm kèm theo thông tin người dùng
$query = "SELECT dg.*, nd.HoTen 
          FROM danhgia dg
          JOIN nguoidung nd ON dg.MaND = nd.MaND
          WHERE dg.MaSP = '$maSP'
          ORDER BY dg.NgayDanhGia DESC
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($connect, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $hoTen = isset($row['HoTen']) ? htmlspecialchars($row['HoTen'], ENT_QUOTES, 'UTF-8') : "Ẩn danh";
    $stars = str_repeat("★", $row['SoSao']) . str_repeat("☆", 5 - $row['SoSao']);

    echo "<div class='review-item'>";
    echo "<div class='review-user'>";
    echo "<img src='https://cdn-icons-png.flaticon.com/128/149/149071.png' alt='User'> $hoTen <span style='color: green;'>✔ Đã mua tại Tam Thái Tử Bakery</span>";
    echo "</div>";
    echo "<div class='review-stars'>$stars</div>";
    echo "<p class='review-text'>" . htmlspecialchars($row['BinhLuan'], ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<div class='review-actions'>";
    echo "<span>Ngày đánh giá: " . htmlspecialchars($row['NgayDanhGia'], ENT_QUOTES, 'UTF-8') . "</span>";
    echo "</div>";
    echo "</div>";
}
?>