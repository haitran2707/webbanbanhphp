<?php
include("connect.php");

// Kiểm tra kết nối
if (!$connect) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

// Truy vấn dữ liệu từ bảng `giohang` và lấy tên người dùng từ bảng `nguoidung`
$query = "SELECT giohang.MaGH, giohang.MaND, giohang.MaSP, giohang.SoLuong, giohang.DonGia, giohang.NgayTao, nguoidung.HoTen
          FROM giohang
          LEFT JOIN nguoidung ON giohang.MaND = nguoidung.MaND
          ORDER BY giohang.NgayTao DESC";

$result = mysqli_query($connect, $query);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($connect));
}

// Thiết lập header để xuất file CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="Gio_Hang.csv"'); // Tên file tải về

// Mở output stream
$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF"); // Thêm BOM UTF-8 để tránh lỗi font tiếng Việt

// Ghi dòng tiêu đề
fputcsv($output, ['STT', 'Mã Giỏ Hàng', 'Họ Tên Người Dùng', 'Mã Sản Phẩm', 'Số Lượng', 'Đơn Giá', 'Ngày Tạo']);

// Ghi dữ liệu từ database vào CSV
$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $stt,
        $row['MaGH'],
        $row['HoTen'],  // Lấy tên người dùng từ bảng `nguoidung`
        $row['MaSP'],
        $row['SoLuong'],
        number_format($row['DonGia'], 0, ',', '.') . " đ",  // Định dạng tiền tệ
        $row['NgayTao']
    ]);
    $stt++;
}

// Đóng output stream
fclose($output);
exit();
?>