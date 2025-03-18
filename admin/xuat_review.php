<?php
include("connect.php");

// Kiểm tra kết nối
if (!$connect) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

// Truy vấn dữ liệu từ bảng `danhgia`, kết hợp với bảng `nguoidung` và `sanpham`
$query = "SELECT dg.MaDG, dg.MaND, nd.HoTen, dg.SoSao, dg.BinhLuan, dg.NgayDanhGia, dg.MaSP, sp.TenSP
          FROM danhgia dg
          JOIN nguoidung nd ON dg.MaND = nd.MaND
          JOIN sanpham sp ON dg.MaSP = sp.MaSP
          ORDER BY dg.NgayDanhGia DESC";

$result = mysqli_query($connect, $query);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($connect));
}

// Thiết lập header để xuất file CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="Danh_Sach_Danh_Gia.csv"'); // Tên file tải về

// Mở output stream
$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF"); // Thêm BOM UTF-8 để tránh lỗi font tiếng Việt

// Ghi dòng tiêu đề
fputcsv($output, ['STT', 'Mã Đánh Giá', 'Sản Phẩm', 'Người Dùng', 'Số Sao', 'Bình Luận', 'Ngày Đánh Giá']);

// Ghi dữ liệu từ database vào CSV
$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $stt,
        $row['MaDG'],
        "{$row['TenSP']} ({$row['MaSP']})", // Tên sản phẩm + mã sản phẩm
        "{$row['HoTen']} ({$row['MaND']})", // Họ tên + mã người dùng
        $row['SoSao'],
        substr($row['BinhLuan'], 0, 50) . "...", // Cắt bớt bình luận để tránh quá dài
        $row['NgayDanhGia']
    ]);
    $stt++;
}

// Đóng output stream
fclose($output);
exit();
?>