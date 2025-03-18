<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_categories.php");
    exit();
}

$maDM = $_GET['id'];

$query = "SELECT * FROM danhmuc WHERE MaDM = '$maDM'";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Danh mục không tồn tại!'); window.location.href='manage_categories.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenDM = mysqli_real_escape_string($connect, $_POST['TenDM']);
    $updateQuery = "UPDATE danhmuc SET TenDM='$tenDM' WHERE MaDM='$maDM'";

    if (mysqli_query($connect, $updateQuery)) {
        echo "<script>alert('Cập nhật danh mục thành công!'); window.location.href='manage_categories.php';</script>";
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
    <title>Chỉnh sửa người dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
        input {
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
        .cancel-btn {
            background-color: #d9534f;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chỉnh sửa người dùng</h2>
        <form action="" method="POST">
            <label for="TenDM">Tên Danh Mục:</label>
            <input type="text" name="TenDM" value="<?php echo $row['TenDM']; ?>" required>

            <button type="submit" class="update-btn">Cập Nhật</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='manage_categories.php'">Hủy</button>
        </form>
    </div>
</body>
</html>