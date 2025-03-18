<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = trim($_POST['hoten']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);
    $diachi = trim($_POST['diachi']);
    $taikhoan = trim($_POST['taikhoan']);
    $matkhau = trim($_POST['password']); // Lưu mật khẩu dạng plaintext (không mã hóa)
    $quyenND = 'Admin'; // Mặc định là admin

    // Kiểm tra tài khoản hoặc email đã tồn tại chưa
    $stmt = $connect->prepare("SELECT * FROM nguoidung WHERE Email = ? OR TaiKhoan = ?");
    $stmt->bind_param("ss", $email, $taikhoan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email hoặc Tài khoản đã tồn tại!');</script>";
    } else {
        // Thêm người dùng mới
        $stmt = $connect->prepare("INSERT INTO nguoidung (MaND, HoTen, Email, SDT, DiaChi, TaiKhoan, MatKhau, QuyenND) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $mand, $hoten, $email, $sdt, $diachi, $taikhoan, $matkhau, $quyenND);
        
        if ($stmt->execute()) {
            echo "<script>alert('Đăng ký thành công!'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi đăng ký!');</script>";
        }
    }

    $stmt->close();
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