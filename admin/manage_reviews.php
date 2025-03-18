<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
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
    <title>Quản lý Đánh Giá</title>
    <style>
        /* Cấu hình chung cho body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Cải thiện tiêu đề */
        h2 {
            text-align: center;
            margin-top: 20px;
            font-size: 24px;
            color: #4CAF50;
        }

        /* Cải thiện bảng: khung viền, màu sắc, padding */
        table {
            width: 80%;
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

        table td {
            background-color: #f9f9f9;
        }

        table tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        /* Cải thiện liên kết trong bảng */
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

        /* Cải thiện nút Thêm mới */
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

        /* Thêm khoảng cách và căn giữa cho footer */
        footer {
            text-align: center;
            background: #2C3E50;
            color: white;
            padding: 15px;
            margin-top: 20px;
        }

        /* Tối ưu hóa khoảng cách trong footer */
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <h2>DANH SÁCH ĐÁNH GIÁ CỦA NGƯỜI DÙNG</h2>
    <table>
        <tr>
            <th>STT</th>
            <th>Mã Đánh Giá</th>
            <th>Sản Phẩm</th>
            <th>Người Dùng</th>
            <th>Số Sao</th>
            <th>Bình Luận</th>
            <th>Ngày Đánh Giá</th>
            <th>Xóa</th>
        </tr>
        <?php
        $result = mysqli_query($connect, "SELECT dg.MaDG, dg.MaND, nd.HoTen, dg.SoSao, dg.BinhLuan, dg.NgayDanhGia, dg.MaSP, sp.TenSP
        FROM danhgia dg
        JOIN nguoidung nd ON dg.MaND = nd.MaND
        JOIN sanpham sp ON dg.MaSP = sp.MaSP");
        $stt = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$stt}</td>";
                echo "<td>{$row['MaDG']}</td>";
                echo "<td>{$row['TenSP']} ({$row['MaSP']})</td>"; 
                echo "<td>{$row['HoTen']} ({$row['MaND']})</td>"; 
                echo "<td>{$row['SoSao']}</td>";
                echo "<td>" . substr($row['BinhLuan'], 0, 50) . "...</td>";
                echo "<td>{$row['NgayDanhGia']}</td>"; 
                echo "<td><a href='delete_reviews.php?id={$row['MaDG']}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a></td>";
                echo "</tr>";
                $stt++;
            }
        } else {
            echo "<tr><td colspan='8' align='center'>Không có dữ liệu</td></tr>";
        }
        ?>
        <tr>
            <td colspan="8" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='index.php'">Trang chủ</button>
            </td>
        </tr>
        <tr>
            <td colspan="14" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='xuat_review.php'">Xuất Excel</button>
            </td>
        </tr>
    </table>
    <footer>
        <p>© 2025 Quản trị hệ thống Tam Thái Tử Bakery</p>
    </footer>
</body>
</html>