<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include_once("connect.php");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chi Tiết Đơn Hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            font-size: 24px;
            color: #4CAF50;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table td a {
            text-decoration: none;
            color: #007BFF;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        table td a:hover {
            background-color: #ddd;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        footer {
            text-align: center;
            background: #2C3E50;
            color: white;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>QUẢN LÝ CHI TIẾT ĐƠN HÀNG</h2>
    <table>
        <tr>
            <th>STT</th>
            <th>Mã Đơn Hàng</th>
            <th>Khách Hàng</th>
            <th>Tên Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Đơn Giá</th>
            <th>Thành Tiền</th>
            <th>Xóa</th>
        </tr>
        <?php
        $query = "SELECT chitietdonhang.MaDH, nguoidung.HoTen, sanpham.TenSP, chitietdonhang.SoLuong, chitietdonhang.DonGia, 
        (chitietdonhang.SoLuong * chitietdonhang.DonGia) AS ThanhTien
        FROM chitietdonhang
        JOIN donhang ON chitietdonhang.MaDH = donhang.MaDH
        JOIN nguoidung ON donhang.MaND = nguoidung.MaND
        JOIN sanpham ON chitietdonhang.MaSP = sanpham.MaSP";

        $result = mysqli_query($connect, $query);
        $stt = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$stt}</td>";
                echo "<td>{$row['MaDH']}</td>";
                echo "<td>{$row['HoTen']}</td>";
                echo "<td>{$row['TenSP']}</td>";
                echo "<td>{$row['SoLuong']}</td>";
                echo "<td>" . number_format($row['DonGia'], 0, ',', '.') . " đ</td>";
                echo "<td>" . number_format($row['ThanhTien'], 0, ',', '.') . " đ</td>";
                echo "<td><a href='delete_orders_details.php?id={$row['MaDH']}&sp={$row['TenSP']}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a></td>";
                echo "</tr>";
                $stt++;
            }
        } else {
                echo "<tr><td colspan='8' align='center'>Không có dữ liệu</td></tr>";
        }
        ?>
         <tr>
            <td colspan="8" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='add_orders_details.php'">Thêm mới</button>
            </td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='index.php'">Trang chủ</button>
            </td>
        </tr>
    </table>
    <footer>
        <p>© 2025 Quản trị hệ thống Tam Thái Tử Bakery</p>
    </footer>
</body>
</html>