<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Kiểm tra tài khoản trong database
    $stmt = $connect->prepare("SELECT * FROM nguoidung WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // So sánh mật khẩu 
        if ($password === $user['MatKhau']) { 
            $_SESSION['MaND'] = $user['MaND'];
            $_SESSION['HoTen'] = $user['HoTen'];
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['QuyenND'] = $user['QuyenND'];

            if ($user['QuyenND'] === 'Admin') {
                header("Location: index.php");
            } else {
                header("Location: ../client/trangchu.php");
            }
            exit();
        } else {
            echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); window.location='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Email không tồn tại!'); window.location='login.php';</script>";
        exit();
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
        <form method="post" action="">
            <div class="form-group">
                <label for="email">Tài khoản Email:</label>
                <input type="text" id="email" name="email" required >
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
            <a href="quen_mk.php">Quên mật khẩu ?</a>
            <a href="dangky.php">Người dùng mới? Đăng nhập ngay!</a>
        </div>
    </div>
</body>
</html>