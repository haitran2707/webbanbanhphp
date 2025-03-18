<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

// Kiểm tra xem form có được gửi không
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $maDH = mysqli_real_escape_string($connect, $_POST['MaDH']);
    $maSP = mysqli_real_escape_string($connect, $_POST['MaSP']);
    $soLuong = mysqli_real_escape_string($connect, $_POST['SoLuong']);
    $donGia = mysqli_real_escape_string($connect, $_POST['DonGia']);

    $query = "INSERT INTO chitietdonhang (MaDH ,MaSP, SoLuong, DonGia) 
              VALUES ('$maDH','$maND', '$soLuong', '$donGia')";

    if (mysqli_query($connect, $query)) {
        echo "<script>alert('Thêm ctdh thành công!'); window.location.href='manage_orders_details.php';</script>";
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
    <title>Thêm Đơn Hàng</title>
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
        <h2>Thêm Chi Tiết Đơn Hàng Mới</h2>
        <form action="" method="POST">
        <label for="MaDH">Mã Đơn Hàng:</label>
        <input type="text" name="MaDH" required>

        <label for="MaSP">Khách Hàng:</label>
        <input type="MaSP" name="MaSP" required>

        <label for="SoLuong">Số Lượng:</label>
        <input type="number" name="SoLuong" required>

        <label for="DonGia">Đơn Giá:</label>
        <input type="number" name="DonGia" required>

            <div class="button-group">
                <button type="submit" name="submit" class="submit-btn">Thêm</button>
                <button type="reset" class="reset-btn" onclick="window.location.href='manage_orders.php'">Hủy</button>
            </div>
        </form>
    </div>

</body>
</html>