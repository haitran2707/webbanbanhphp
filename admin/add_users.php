<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
include_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maND = $_POST['MaND'];
    $hoTen = $_POST['HoTen'];
    $email = $_POST['Email'];
    $sdt = $_POST['SDT'];
    $diaChi = $_POST['DiaChi'];
    $taiKhoan = $_POST['TaiKhoan'];
    $matKhau = $_POST['MatKhau'];
    $quyenND = $_POST['QuyenND'];

    $checkUser = mysqli_query($connect, "SELECT * FROM nguoidung WHERE MaND='$maND'");
    if (mysqli_num_rows($checkUser) > 0) {
        echo "<script>alert('Mã người dùng đã tồn tại!');</script>";
    } else {
        $query = "INSERT INTO nguoidung (MaND, HoTen, Email, SDT, DiaChi, TaiKhoan, MatKhau, QuyenND) VALUES ('$maND', '$hoTen', '$email', '$sdt', '$diaChi', '$taiKhoan', '$matKhau', '$quyenND')";
        if (mysqli_query($connect, $query)) {
            echo "<script>alert('Thêm người dùng thành công!'); window.location.href='manage_users.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm người dùng: " . mysqli_error($connect) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px #ccc;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm Người Dùng</h2>
        <form method="POST">
            <label for="MaND">Mã Người Dùng:</label>
            <input type="text" name="MaND" required>
            
            <label for="HoTen">Họ Tên:</label>
            <input type="text" name="HoTen" required>
            
            <label for="Email">Email:</label>
            <input type="email" name="Email" required>
            
            <label for="SDT">Số Điện Thoại:</label>
            <input type="text" name="SDT" required>
            
            <label for="DiaChi">Địa Chỉ:</label>
            <input type="text" name="DiaChi" required>
            
            <label for="TaiKhoan">Tài Khoản:</label>
            <input type="text" name="TaiKhoan" required>
            
            <label for="MatKhau">Mật Khẩu:</label>
            <input type="password" name="MatKhau" required>
            
            <label for="QuyenND">Quyền Người Dùng:</label>
            <select name="QuyenND">
                <option value="admin">Admin</option>
                <option value="user">Khách Hàng</option>
            </select>
            
            <button type="submit">Thêm Người Dùng</button>
            <button type="reset" onclick="window.location.href='manage_users.php'">Hủy</button>
        </form>
    </div>
</body>
</html>