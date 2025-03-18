<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Kiểm tra email có tồn tại không
    $stmt = $connect->prepare("SELECT * FROM nguoidung WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Tạo mật khẩu mới ngẫu nhiên
        $new_password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);

        // Cập nhật mật khẩu trong database
        $stmt = $connect->prepare("UPDATE nguoidung SET MatKhau = ? WHERE Email = ?");
        $stmt->bind_param("ss", $new_password, $email);
        if ($stmt->execute()) {
            echo "<script>alert('Mật khẩu mới của bạn là: $new_password'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra, vui lòng thử lại!'); window.location='quen_mk.php';</script>";
        }
    } else {
        echo "<script>alert('Email không tồn tại trong hệ thống!'); window.location='quen_mk.php';</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #226B7F;
        }
        .container {
            background-color: #ddd;
            width: 350px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            font-size: 24px;
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
            color: #226B7F;
        }
        .form-group label {
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 94%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus {
            border-color: rgb(242, 108, 108);
            outline: none;
        }
        .btn {
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            gap: 5px; 
            padding: 10px;
            background-color: #226B7F;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #185460;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔑Quên mật khẩu</h2>
        <hr>
        <form method="post" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Nhập vào email của bạn" required>
            </div>
            <button class="btn" type="submit">Gửi yêu cầu</button>
        </form>
        <br>
        <div class="footer-links">
            <a href="login.php">Quay lại Đăng Nhập</a>
        </div>
    </div>
</body>
</html>