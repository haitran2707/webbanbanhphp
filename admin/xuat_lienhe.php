<?php
include("connect.php");

// Kiểm tra kết nối
if (!$connect) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

// Truy vấn dữ liệu từ bảng `thongtinlienhe` và lấy tên người dùng từ bảng `nguoidung`
$query = "SELECT thongtinlienhe.*, nguoidung.HoTen AS HoTenNguoiDung 
          FROM thongtinlienhe 
          INNER JOIN nguoidung ON thongtinlienhe.MaND = nguoidung.MaND 
          ORDER BY thongtinlienhe.NgayGui DESC";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($connect));
}

// Thiết lập header để xuất file CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="Thong_Tin_Lien_He.csv"'); // Tên file tải về

// Mở output stream
$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF"); // Thêm BOM UTF-8 để tránh lỗi font tiếng Việt

// Ghi dòng tiêu đề
fputcsv($output, ['STT', 'Mã Liên Hệ', 'Họ Tên Người Dùng', 'Email', 'Số Điện Thoại', 'Địa Chỉ', 'Nội Dung', 'Ngày Gửi']);

// Ghi dữ liệu từ database vào CSV
$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $stt,
        $row['MaLH'],
        $row['HoTenNguoiDung'],
        $row['Email'],
        $row['SDT'],
        $row['DiaChi'],
        $row['NoiDung'], // Ghi toàn bộ nội dung
        $row['NgayGui']
    ]);
    $stt++;
}

// Đóng output stream
fclose($output);
exit();
?>