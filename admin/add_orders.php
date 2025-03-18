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
    $maDH = mysqli_real_escape_string($connect, $_POST['MaDH']);
    $maND = mysqli_real_escape_string($connect, $_POST['MaND']);
    $ngayTao = mysqli_real_escape_string($connect, $_POST['NgayTao']);
    $trangThai = mysqli_real_escape_string($connect, $_POST['TrangThai']);
    $tongTien = mysqli_real_escape_string($connect, $_POST['TongTien']);
    $diaChiGiaoHang = mysqli_real_escape_string($connect, $_POST['DiaChiGiaoHang']);
    $phuongThucThanhToan = mysqli_real_escape_string($connect, $_POST['PhuongThucThanhToan']);

    $query = "INSERT INTO donhang (MaDH ,MaND, NgayTao, TrangThai, TongTien, DiaChiGiaoHang, PhuongThucThanhToan) 
              VALUES ('$maDH','$maND', '$ngayTao', '$trangThai', '$tongTien', '$diaChiGiaoHang', '$phuongThucThanhToan')";

    if (mysqli_query($connect, $query)) {
        echo "<script>alert('Thêm đơn hàng thành công!'); window.location.href='manage_orders.php';</script>";
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
        <h2>Thêm Đơn Hàng Mới</h2>
        <form action="" method="POST">
        
            <label for="MaDH">Mã Đơn Hàng:</label>
            <input type="text" name="MaDH" required>

            <label for="MaND">Khách Hàng:</label>
            <input type="text" name="MaND" required>
            <?php
            $result = mysqli_query($connect, "SELECT MaND, HoTen FROM nguoidung");
            while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['MaND']}'>{$row['HoTen']}</option>";
            }
            ?>

            <label for="NgayTao">Ngày Tạo:</label>
            <input type="datetime-local" name="NgayTao" required>

            <label for="TrangThai">Trạng Thái:</label>
            <input type="text" name="TrangThai" required>

            <label for="TongTien">Tổng Tiền:</label>
            <input type="number" name="TongTien" required>

            <label for="DiaChiGiaoHang">Địa Chỉ Giao Hàng:</label>
            <textarea name="DiaChiGiaoHang" required></textarea>

            <label for="PhuongThucThanhToan">Phương Thức Thanh Toán:</label>
            <input type="text" name="PhuongThucThanhToan" required>

            <div class="button-group">
                <button type="submit" name="submit" class="submit-btn">Thêm</button>
                <button type="reset" class="reset-btn" onclick="window.location.href='manage_orders.php'">Hủy</button>
            </div>
        </form>
    </div>

</body>
</html>