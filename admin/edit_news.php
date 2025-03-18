<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_news.php");
    exit();
}

$maTT = $_GET['id'];

$query = "SELECT * FROM tintuc WHERE MaTT = '$maTT'";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Tin tức không tồn tại!'); window.location.href='manage_news.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);
$oldImage = $row['HinhAnh']; // Lưu đường dẫn ảnh cũ

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tieuDe = mysqli_real_escape_string($connect, $_POST['TieuDe']);
    $ngayDang = mysqli_real_escape_string($connect, $_POST['NgayDang']);
    $noiDung = mysqli_real_escape_string($connect, $_POST['NoiDung']);

     // Xử lý ảnh
     if (!empty($_FILES['HinhAnh']['name'])) {
        $target_dir = "images/"; // Thư mục lưu ảnh
        $file_name = basename($_FILES["HinhAnh"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra định dạng ảnh hợp lệ
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Chỉ cho phép định dạng JPG, JPEG, PNG, GIF!');</script>";
        } else {
            // Di chuyển file tải lên vào thư mục
            if (move_uploaded_file($_FILES["HinhAnh"]["tmp_name"], $target_file)) {
                // Xóa ảnh cũ nếu có
                if (!empty($oldImage) && file_exists($oldImage)) {
                    unlink($oldImage);
                }
                $newImage = $target_file; // Đường dẫn ảnh mới
            } else {
                echo "<script>alert('Lỗi khi tải ảnh lên!');</script>";
                $newImage = $oldImage; // Giữ nguyên ảnh cũ nếu upload thất bại
            }
        }
    } else {
        $newImage = $oldImage; // Nếu không chọn ảnh mới, giữ nguyên ảnh cũ
    }

    $updateQuery = "UPDATE tintuc SET TieuDe='$tieuDe', NoiDung='$noiDung', HinhAnh='$newImage', NgayDang= '$ngayDang' WHERE MaTT='$maTT'";

    if (mysqli_query($connect, $updateQuery)) {
        echo "<script>alert('Cập nhật tin tức thành công!'); window.location.href='manage_news.php';</script>";
    } else {
        echo "Lỗi: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa tin tức</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .update-btn {
            background-color: #4CAF50;
            color: white;
        }
        .update-btn:hover {
            background-color: #45a049;
        }
        .cancel-btn {
            background-color: #d9534f;
            color: white;
        }
        .cancel-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chỉnh sửa tin tức</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="TieuDe">Tiêu Đề:</label>
            <input type="text" name="TieuDe" value="<?php echo $row['TieuDe']; ?>" required>

            <label for="NoiDung">Nội Dung:</label>
            <textarea name="NoiDung" value="<?php echo $row['Noi Dung']; ?>" required></textarea>

            <label for="NgayDang">Ngày Đăng:</label>
            <input type="datetime-local" name="NgayDang" value="<?php echo $row['NgayDang']; ?>" required>
            
            <label for="HinhAnh">Hình Ảnh:</label>
            <input type="file" name="HinhAnh">
            <br>
            <img src="<?php echo htmlspecialchars($row['HinhAnh']); ?>" alt="Hình ảnh" width="80">

            <button type="submit" class="update-btn">Cập Nhật</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='manage_news.php'">Hủy</button>
        </form>
    </div>
</body>
</html>