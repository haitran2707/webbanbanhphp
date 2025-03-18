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
    $maLH = mysqli_real_escape_string($connect, $_POST['MaLH']);
    $maND = mysqli_real_escape_string($connect, $_POST['MaND']);
    $email = mysqli_real_escape_string($connect, $_POST['Email']);
    $sdt = mysqli_real_escape_string($connect, $_POST['SDT']);
    $diaChi = mysqli_real_escape_string($connect, $_POST['DiaChi']);
    $noiDung = mysqli_real_escape_string($connect, $_POST['NoiDung']);
    $ngayGui = mysqli_real_escape_string($connect, $_POST['NgayGui']);

    $query = "INSERT INTO thongtinlienhe (MaLH, MaND ,Email, SDT, DiaChi, NoiDung, NgayGui) 
              VALUES ( '$maLH','$maND','$email', '$sdt', '$diaChi', '$noiDung', '$ngayGui')";

    if (mysqli_query($connect, $query)) {
        echo "<script>alert('Thêm thông tin liên hệ thành công!'); window.location.href='manage_contacts.php';</script>";
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
    <title>Thêm Thông Tin Liên Hệ</title>
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

        input, textarea {
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
        <h2>Thêm Thông Tin Liên Hệ Mới</h2>
        <form action="" method="POST">
        
            <label for="MaLH">Mã Liên Hệ:</label>
            <input type="text" name="MaLH" required>

            <label for="MaND">Người Dùng:</label>
            <select name="MaND" required>
                <option value="">-- Chọn Người Dùng --</option>
                <?php
                $result = mysqli_query($connect, "SELECT MaND, HoTen FROM nguoidung");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['MaND']}'>{$row['HoTen']}</option>";
                }
                ?>
            </select>
            
            <label for="SDT">Số Điện Thoại:</label>
            <input type="text" name="SDT" required>

            <label for="Email">Email:</label>
            <input type="text" name="Email" required>

            <label for="NoiDung">Nội Dung:</label>
            <input type="text" name="NoiDung" required>

            <label for="DiaChi">Địa Chỉ:</label>
            <input type="text" name="DiaChi" required>

            <label for="NgayGui">Ngày Gửi:</label>
            <input type="datetime-local" name="NgayGui" required>

            <div class="button-group">
                <button type="submit" name="submit" class="submit-btn">Thêm</button>
                <button type="reset" class="reset-btn" onclick="window.location.href='manage_contacts.php'">Hủy</button>
            </div>
        </form>
    </div>

</body>
</html>