<?php
session_start();
include_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra dữ liệu đầu vào
    if (!isset($_POST['taikhoan']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.location='login.php';</script>";
        exit();
    }

    $taikhoan = mysqli_real_escape_string($connect, $_POST['taikhoan']); // Tên tài khoản
    $email = mysqli_real_escape_string($connect, $_POST['email']); // Email
    $password = mysqli_real_escape_string($connect, $_POST['password']); // Mật khẩu

    // Truy vấn kiểm tra tài khoản dựa vào tên tài khoản hoặc email
    $query = "SELECT MaND, TaiKhoan, Email, MatKhau FROM nguoidung 
              WHERE TaiKhoan = '$taikhoan' AND Email = '$email' AND MatKhau = '$password'";
    $result = mysqli_query($connect, $query);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($connect));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Lưu thông tin đăng nhập vào session
        $_SESSION['MaND'] = $row['MaND'];
        $_SESSION['user'] = $row['TaiKhoan']; // Lưu tên tài khoản thay vì email

        // Nếu chọn "Luôn nhớ tài khoản", lưu vào cookie trong 7 ngày
        if (isset($_POST['remember'])) {
            setcookie("remember_user", $row['TaiKhoan'], time() + (7 * 24 * 60 * 60), "/");
        }

        // Chuyển hướng đến trang chủ của người dùng
        header("Location: trangchu.php");
        exit();
    } else {
        echo "<script>alert('Sai tài khoản, email hoặc mật khẩu!'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Đăng Nhập</title>
    <style>
        hr {
            border: none; 
            height: 1px; 
            background-color: white; 
            margin: 10px 0; 
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color:#226B7F;
        }
        .login-container {
            background-color:#ddd;
            width: 350px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .login-container h2 {
            font-size: 24px;
            color: #226B7F;
            margin-bottom: 20px;
        }
        .login-container h2::before {
            content: "🔒";
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
        .action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            color: #226B7F;
        }
        .checkbox-group input {
            margin: 0;
        }
        .btn-login {
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 5px; 
            padding: 8px;
            background-color: #226B7F;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            flex-grow: 0;
            margin-left: 10px;
        }
        .btn-login img {
            width: 16px; 
            height: 16px;
        }
        .btn-login:hover {
            background-color: #185460;
        }
        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 12px;
        }
        .footer-links a {
            color: #226B7F;
            text-decoration: none;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Đăng Nhập</h2>
    <hr>
    <form method="post" action="" onsubmit="saveRememberMe()"> 

        <div class="form-group">
                <label for="taikhoan">Tên tài khoản:</label>
                <input type="text" id="taikhoan" name="taikhoan" placeholder="Nhập tên tài khoản" required>
            </div>

        <div class="form-group">
            <label for="email"> Email:</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="action-row">
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Luôn nhớ tài khoản</label>
            </div>
            <button type="submit" class="btn-login">
                <span>Đăng nhập</span>
                <img src="https://cdn-icons-png.flaticon.com/128/6711/6711579.png" alt="Sign In Icon">
            </button>                
        </div>
    </form>
    <br><hr>
    <div class="footer-links">
        <a href="quen_mk.php">Quên mật khẩu?</a>
        <a href="dangky.php">Người dùng mới? Đăng ký ngay!</a>
        <a href="login.php">Đăng xuất</a>
    </div>
</div>

<script>
    // Kiểm tra nếu email đã được lưu thì tự động điền vào ô input
    document.addEventListener("DOMContentLoaded", function () {
        if (localStorage.getItem("rememberedEmail")) {
            document.getElementById("email").value = localStorage.getItem("rememberedEmail");
            document.getElementById("remember").checked = true;
        }
    });

    function saveRememberMe() {
        let remember = document.getElementById("remember").checked;
        let email = document.getElementById("email").value;

        if (remember) {
            localStorage.setItem("rememberedEmail", email);
        } else {
            localStorage.removeItem("rememberedEmail");
        }
    }
</script>

</body>
</html>