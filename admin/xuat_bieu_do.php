<?php
$connect = new mysqli("localhost", "root", "", "webbanbanh");
if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Bao_Cao_Doanh_Thu.csv"');
$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF"); // Thêm BOM UTF-8 để hiển thị tiếng Việt đúng

fputcsv($output, ['BÁO CÁO DOANH THU']);// Tiêu đề file
fputcsv($output, []); // Dòng trống
fputcsv($output, ['Tháng', 'Doanh thu (VNĐ)']);

// Dữ liệu doanh thu theo tháng
for ($i = 1; $i <= 12; $i++) {
    $query = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành' AND MONTH(NgayTao) = $i");
    $data = $query->fetch_assoc();
    fputcsv($output, ["Tháng $i", $data['total'] ?: 0]);
}

// Dữ liệu doanh thu theo quý
fputcsv($output, []); // Dòng trống
fputcsv($output, ['Quý', 'Doanh thu (VNĐ)']);
for ($i = 1; $i <= 4; $i++) {
    $query = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành' AND QUARTER(NgayTao) = $i");
    $data = $query->fetch_assoc();
    fputcsv($output, ["Quý $i", $data['total'] ?: 0]);
}

fclose($output);
exit();
?>