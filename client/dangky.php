<?php
session_start();
include_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = mysqli_real_escape_string($connect, $_POST['hoten']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $sdt = mysqli_real_escape_string($connect, $_POST['sdt']);
    $diachi = mysqli_real_escape_string($connect, $_POST['diachi']);
    $taikhoan = mysqli_real_escape_string($connect, $_POST['taikhoan']);
    $matkhau = mysqli_real_escape_string($connect,$_POST['password']);
    $quyenND = 'Khách hàng'; // Mặc định là khách hàng

    // Kiểm tra tài khoản hoặc email đã tồn tại chưa
    $check_user = "SELECT * FROM nguoidung WHERE Email = '$email' OR TaiKhoan = '$taikhoan'";
    $result = mysqli_query($connect, $check_user);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email hoặc Tài khoản đã tồn tại!');</script>";
    } else {
        // Thêm người dùng mới
        $query = "INSERT INTO nguoidung (HoTen, Email, SDT, DiaChi, TaiKhoan, MatKhau, QuyenND) 
                  VALUES ('$hoten', '$email', '$sdt', '$diachi', '$taikhoan', '$matkhau', '$quyenND')";
        
        if (mysqli_query($connect, $query)) {
            echo "<script>alert('Đăng ký thành công!'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi đăng ký!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
         body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #226B7F;
    }
    .container {
        background-color: #ddd;
        width: 90%; /* Chiếm 90% chiều rộng màn hình */
        max-width: 400px; /* Giới hạn chiều rộng tối đa */
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    h2 {
        color: #226B7F;
        margin-bottom: 20px;
    }
    hr {
            border: none; 
            height: 1px; 
            background-color: white; 
            margin: 10px 0; 
    }
    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #226B7F;
    }
    .form-group input {
        width: 95%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .form-group input:focus {
            border-color: rgb(242, 108, 108);
            outline: none;
    }
    .btn {
        background-color: #226B7F;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
        text-align: center;
        display: inline-block;
    }
    .btn:hover {
        background-color: #185460;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝Đăng ký tài khoản</h2>
        <hr>
        <form method="post" action="">
        <div class="form-group">
                <label for="hoten">Họ và Tên:</label>
                <input type="text" id="hoten" name="hoten" required>
            </div>

            <div class="form-group">
                <label for="taikhoan">Tên tài khoản:</label>
                <input type="text" id="taikhoan" name="taikhoan" placeholder="Nhập tên tài khoản" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Nhập email" required>
            </div>

            <div class="form-group">
                <label for="sdt">Số điện thoại:</label>
                <input type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <div class="form-group">
                <label for="diachi">Địa Chỉ:</label>
                <input type="text" id="diachi" name="diachi" placeholder="Nhập địa chỉ" required>
            </div>

            <button class="btn" type="submit">Đăng ký</button>
            <button class="btn" type="submit" onclick="window.location.href='login.php'">Hủy</button>
        </form>
    </div>
</body>
</html>