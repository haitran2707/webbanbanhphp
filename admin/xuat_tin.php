<?php
include("connect.php");

// Kiểm tra kết nối
if (!$connect) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

// Truy vấn dữ liệu từ bảng `tintuc` và lấy tên người đăng từ bảng `nguoidung`
$query = "SELECT tintuc.*, nguoidung.HoTen AS HoTenNguoiDung 
          FROM tintuc
          INNER JOIN nguoidung ON tintuc.MaND = nguoidung.MaND 
          ORDER BY tintuc.NgayDang DESC";

$result = mysqli_query($connect, $query);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($connect));
}

// Thiết lập header để xuất file CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="Tin_Tuc.csv"'); // Tên file tải về

// Mở output stream
$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF"); // Thêm BOM UTF-8 để tránh lỗi font tiếng Việt

// Ghi dòng tiêu đề
fputcsv($output, ['STT', 'Mã Tin Tức', 'Người Đăng', 'Tiêu Đề', 'Nội Dung', 'Ngày Đăng', 'Hình Ảnh']);

// Ghi dữ liệu từ database vào CSV
$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $stt,
        $row['MaTT'],
        $row['HoTenNguoiDung'],  // Lấy tên người đăng từ bảng `nguoidung`
        $row['TieuDe'],
        substr($row['NoiDung'], 0, 100) . "...",  // Giới hạn nội dung để tránh quá dài
        $row['NgayDang'],
        $row['HinhAnh']  // Đường dẫn ảnh
    ]);
    $stt++;
}

// Đóng output stream
fclose($output);
exit();
?>