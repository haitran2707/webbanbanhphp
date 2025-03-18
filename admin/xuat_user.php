<?php
include("connect.php");

// Kiểm tra kết nối
if (!$connect) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

// Truy vấn dữ liệu từ bảng `nguoidung`
$query = "SELECT * FROM nguoidung ORDER BY MaND ASC";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($connect));
}

// Thiết lập header để xuất file CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="Nguoi_Dung.csv"'); // Tên file tải về

// Mở output stream
$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF"); // Thêm BOM UTF-8 để tránh lỗi font tiếng Việt

// Ghi dòng tiêu đề
fputcsv($output, ['STT', 'Mã Người Dùng', 'Họ Tên', 'Email', 'SĐT', 'Địa Chỉ', 'Tài Khoản', 'Mật Khẩu', 'Quyền Người Dùng']);

// Ghi dữ liệu từ database vào CSV
$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $stt,
        $row['MaND'],
        $row['HoTen'],
        $row['Email'],
        $row['SDT'],
        substr($row['DiaChi'], 0, 50) . "...", // Giới hạn độ dài địa chỉ
        $row['TaiKhoan'],
        $row['MatKhau'], // Có thể bỏ trường này nếu không muốn xuất mật khẩu
        $row['QuyenND']
    ]);
    $stt++;
}

// Đóng output stream
fclose($output);
exit();
?>