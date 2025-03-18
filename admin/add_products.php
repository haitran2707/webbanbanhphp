<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

// Kiểm tra xem form có được gửi không
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $maSP = mysqli_real_escape_string($connect, $_POST['MaSP']);
    $tenSP = mysqli_real_escape_string($connect, $_POST['TenSP']);
    $moTa = mysqli_real_escape_string($connect, $_POST['MoTa']);
    $gia = mysqli_real_escape_string($connect, $_POST['Gia']);
    $soLuong = mysqli_real_escape_string($connect, $_POST['SoLuong']);
    $maDM = mysqli_real_escape_string($connect, $_POST['MaDM']);

    // Xử lý file tải lên
    $target_dir = "images/"; // Thư mục lưu trữ file tải lên
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["HinhAnh"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra xem file có phải là ảnh thật không
    $check = getimagesize($_FILES["HinhAnh"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File không phải là ảnh.";
        $uploadOk = 0;
    }

    // Kiểm tra kích thước file
    if ($_FILES["HinhAnh"]["size"] > 500000) {
        echo "File quá lớn.";
        $uploadOk = 0;
    }

    // Chỉ cho phép các định dạng file hình ảnh nhất định
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Chỉ cho phép file JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    // Kiểm tra nếu $uploadOk là 0 vì lỗi
    if ($uploadOk == 0) {
        echo "File không được tải lên.";
    } else {
        if (move_uploaded_file($_FILES["HinhAnh"]["tmp_name"], $target_file)) {
            // Lưu đường dẫn hình ảnh vào cơ sở dữ liệu
            $hinhAnh = $target_file;
            $query = "INSERT INTO sanpham (MaSP, TenSP, MoTa, Gia, SoLuong, HinhAnh, MaDM) 
                      VALUES ('$maSP', '$tenSP', '$moTa', '$gia', '$soLuong', '$hinhAnh', '$maDM')";

            if (mysqli_query($connect, $query)) {
                echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='manage_products.php';</script>";
            } else {
                echo "Lỗi: " . mysqli_error($connect);
            }
        } else {
            echo "Có lỗi xảy ra khi tải lên file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Định dạng nền */
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form Container */
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Fields */
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            height: 80px;
        }

        /* Nút bấm */
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        button {
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn {
            background-color: #28a745;
            color: white;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        .reset-btn {
            background-color: #dc3545;
            color: white;
        }

        .reset-btn:hover {
            background-color: #c82333;
        }

        /* Responsive */
        @media (max-width: 500px) {
            .form-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Thêm Sản Phẩm Mới</h2>
        <form action="" method="POST" enctype="multipart/form-data" >
        
            <label for="MaSP">Mã Sản Phẩm:</label>
            <input type="text" name="MaSP" required>

            <label for="TenSP">Tên Sản Phẩm:</label>
            <input type="text" name="TenSP" required>

            <label for="MoTa">Mô Tả:</label>
            <textarea name="MoTa" required></textarea>

            <label for="Gia">Giá:</label>
            <input type="number" name="Gia" required>

            <label for="SoLuong">Số Lượng:</label>
            <input type="number" name="SoLuong" required>

            <label for="HinhAnh">Hình Ảnh:</label>
            <input type="file" name="HinhAnh" required>

            <label for="MaDM">Danh Mục:</label>
            <select name="MaDM" required>
                <?php
                $result = mysqli_query($connect, "SELECT MaDM, TenDM FROM danhmuc");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['MaDM']}'>{$row['TenDM']}</option>";
                }
                ?>
            </select>

            <div class="button-group">
                <button type="submit" name="submit" class="submit-btn">Thêm</button>
                <button type="reset" class="reset-btn" onclick="window.location.href='manage_products.php'">Hủy</button>
            </div>
        </form>
    </div>

</body>
</html>