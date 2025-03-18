<?php
include("connect.php");

if (isset($_POST["import"])) {
    $file = $_FILES["file"]["tmp_name"];

    if ($file) {
        $handle = fopen($file, "r");
        $firstLine = fgets($handle); // Đọc dòng đầu tiên để kiểm tra BOM
        $firstLine = preg_replace('/\xEF\xBB\xBF/', '', $firstLine); // Xóa BOM nếu có
        rewind($handle); // Đưa con trỏ file về đầu
        fgetcsv($handle); // Bỏ qua dòng tiêu đề

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Lấy dữ liệu từ từng dòng CSV
            
            $TenSP = mysqli_real_escape_string($connect, $row[2]); // Cột 3: Tên sản phẩm
            $TenDM = mysqli_real_escape_string($connect, $row[3]); // Cột 4: Tên danh mục
            $MoTa = mysqli_real_escape_string($connect, $row[4]); // Cột 5: Mô tả
            $Gia = str_replace(['.', ' đ'], '', $row[5]); // Xóa dấu chấm và ký hiệu "đ"
            $Gia = mysqli_real_escape_string($connect, $Gia);
            $SoLuong = mysqli_real_escape_string($connect, $row[6]); // Cột 7: Số lượng
            $HinhAnh = mysqli_real_escape_string($connect, $row[7]); // Cột 8: Hình ảnh (URL)

            // Lấy `MaDM` từ `TenDM`
            $queryDanhMuc = "SELECT MaDM FROM danhmuc WHERE TenDM = '$TenDM' LIMIT 1";
            $resultDanhMuc = mysqli_query($connect, $queryDanhMuc);
            $rowDanhMuc = mysqli_fetch_assoc($resultDanhMuc);

            if ($rowDanhMuc) {
                $MaDM = $rowDanhMuc['MaDM'];
            } else {
                // Nếu danh mục chưa tồn tại, thêm mới danh mục vào bảng `danhmuc`
                mysqli_query($connect, "INSERT INTO danhmuc (TenDM) VALUES ('$TenDM')");
                $MaDM = mysqli_insert_id($connect); // Lấy `MaDM` vừa thêm
            }

            // Thêm vào bảng `sanpham`
            $query = "INSERT INTO sanpham (TenSP, MaDM, MoTa, Gia, SoLuong, HinhAnh) 
                      VALUES ('$TenSP', '$MaDM', '$MoTa', '$Gia', '$SoLuong', '$HinhAnh')";
            mysqli_query($connect, $query);
        }

        fclose($handle);
        echo "Nhập dữ liệu thành công!";
    } else {
        echo "Vui lòng chọn file CSV.";
    }
}
?>