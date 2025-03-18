<?php
$connect = mysqli_connect("localhost", "root", "", "webbanbanh"); // Thay thế thông tin database

if (!$connect) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

// Truy vấn dữ liệu đơn hàng
$query = "SELECT donhang.*, nguoidung.HoTen AS HoTenKhachHang
          FROM donhang
          INNER JOIN nguoidung ON donhang.MaND = nguoidung.MaND
          ORDER BY donhang.NgayTao DESC";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($connect));
}

// Thiết lập header để tải file về
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="Don_Hang.csv"'); //Tên file

// Mở output stream
$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF"); // Thêm BOM UTF-8 để hiển thị tiếng Việt đúng

// Ghi dòng tiêu đề
fputcsv($output, ['STT', 'Mã Đơn Hàng', 'Họ Tên Khách Hàng', 'Ngày Tạo', 'Trạng Thái', 'Tổng Tiền', 'Địa Chỉ Giao Hàng', 'Phương Thức Thanh Toán']);

// Ghi dữ liệu từ database
$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $stt,
        $row['MaDH'],
        $row['HoTenKhachHang'],
        $row['NgayTao'],
        $row['TrangThai'],
        number_format($row['TongTien'], 0, ',', '.') . " đ",
        $row['DiaChiGiaoHang'],
        $row['PhuongThucThanhToan']
    ]);
    $stt++;
}

// Đóng output stream
fclose($output);
exit();
?>