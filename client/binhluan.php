<?php
$connect = mysqli_connect("localhost", "root", "", "webbanbanh");
if (!$connect) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}
// Xử lý thêm đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maSP = intval($_POST['masp']);
    $hoTen = mysqli_real_escape_string($connect, $_POST['hoten']);
    $soSao = intval($_POST['sosao']);
    $binhLuan = mysqli_real_escape_string($connect, $_POST['binhluan']);
    $date = new DateTime("now", new DateTimeZone("Asia/Ho_Chi_Minh"));
    $ngayDanhGia = $date->format("Y-m-d H:i:s");

     // Kiểm tra nếu người dùng đã tồn tại trong cơ sở dữ liệu
     $query_user = "SELECT MaND FROM nguoidung WHERE HoTen = '$hoTen'";
     $result_user = mysqli_query($connect, $query_user);
     $row_user = mysqli_fetch_assoc($result_user);
 
     if ($row_user) {
         // Nếu người dùng tồn tại, lấy MaND của họ
         $maND = $row_user['MaND'];
     } else {
         // Nếu người dùng chưa tồn tại, thêm họ vào database
         $query_insert_user = "INSERT INTO nguoidung (HoTen) VALUES ('$hoTen')";
         mysqli_query($connect, $query_insert_user);
         $maND = mysqli_insert_id($connect); // Lấy ID vừa chèn vào
     }

    $query = "INSERT INTO danhgia (MaSP, MaND, SoSao, BinhLuan, NgayDanhGia) 
              VALUES ('$maSP', '$maND', '$soSao', '$binhLuan', '$ngayDanhGia')";

    if (mysqli_query($connect, $query)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}
// Nhận MaSP từ GET hoặc đặt mặc định (Thay bằng MaSP thực tế)
$maSP = isset($_GET['masp']) ? intval($_GET['masp']) : 1;

// Số đánh giá hiển thị mỗi lần
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

// Truy vấn đánh giá của sản phẩm cụ thể
$query = "SELECT dg.MaDG, dg.MaND, nd.HoTen, dg.SoSao, dg.BinhLuan, dg.NgayDanhGia 
          FROM danhgia dg
          JOIN nguoidung nd ON dg.MaND = nd.MaND
          WHERE dg.MaSP = '$maSP'
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($connect, $query);
$totalReviews = mysqli_num_rows($result);

// Tính điểm trung bình
$query_avg = "SELECT AVG(SoSao) as avg_rating FROM danhgia WHERE MaSP = '$maSP'";
$avg_result = mysqli_query($connect, $query_avg);
$averageRating = mysqli_fetch_assoc($avg_result)['avg_rating'] ?? 0;
$averageRating = round($averageRating, 1); // Làm tròn 1 chữ số

// Tính phần trăm từng mức sao
$star_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$query_stars = "SELECT SoSao, COUNT(*) as count FROM danhgia WHERE MaSP = '$maSP' GROUP BY SoSao";
$star_result = mysqli_query($connect, $query_stars);
while ($row = mysqli_fetch_assoc($star_result)) {
    $star_counts[$row['SoSao']] = $row['count'];
}

// Tính phần trăm
$percentages = [];
for ($i = 5; $i >= 1; $i--) {
    $percentages[$i] = ($totalReviews > 0) ? round(($star_counts[$i] / $totalReviews) * 100, 1) : 0;
}
?>