<?php
include("connect.php");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="danh_sach_san_pham.csv"');

$output = fopen("php://output", "w");
fputs($output, "\xEF\xBB\xBF"); // Thêm BOM UTF-8 để hiển thị tiếng Việt đúng

// Ghi dòng tiêu đề
fputcsv($output, array('STT', 'Mã sản phẩm', 'Tên sản phẩm', 'Danh mục', 'Mô tả', 'Giá', 'Số lượng', 'Hình ảnh'));

// Lấy dữ liệu từ database
$result = mysqli_query($connect, "SELECT sanpham.*, danhmuc.TenDM 
    FROM sanpham 
    LEFT JOIN danhmuc ON sanpham.MaDM = danhmuc.MaDM");

$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array(
        $stt,
        $row['MaSP'],
        $row['TenSP'],
        (!empty($row['TenDM']) ? $row['TenDM'] : "Chưa có danh mục"), // Hiển thị tên danh mục hoặc thông báo nếu không có
        substr($row['MoTa'], 0, 70), // Cắt mô tả nếu quá dài
        number_format($row['Gia'], 0, ',', '.') . " đ", // Định dạng tiền tệ
        $row['SoLuong'],
        $row['HinhAnh']
    ));
    $stt++;
}

fclose($output);
exit();
?>