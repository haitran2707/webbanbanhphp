<?php
include("connect.php");

if (isset($_POST["import"])) {
    $file = $_FILES["file"]["tmp_name"];

    if ($file) {
        $handle = fopen($file, "r");
        $firstLine = fgets($handle);
        $firstLine = preg_replace('/\xEF\xBB\xBF/', '', $firstLine);
        rewind($handle);
        fgetcsv($handle); // Bỏ qua dòng tiêu đề

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Chỉ lấy Tên danh mục từ file CSV
            $TenDM = mysqli_real_escape_string($connect, $row[0]); // Cột 1: Tên danh mục

            // Kiểm tra xem danh mục đã tồn tại chưa (chỉ kiểm tra theo TenDM)
            $queryCheck = "SELECT * FROM danhmuc WHERE TenDM = '$TenDM'";
            $resultCheck = mysqli_query($connect, $queryCheck);

            if (mysqli_num_rows($resultCheck) == 0) {
                // Chưa tồn tại -> Thêm mới vào database (MaDM sẽ tự động tăng)
                $queryInsert = "INSERT INTO danhmuc (TenDM) VALUES ('$TenDM')";
                mysqli_query($connect, $queryInsert);
            }
        }

        fclose($handle);
        echo "Nhập danh mục thành công!";
    } else {
        echo "Vui lòng chọn file CSV.";
    }
}
?>