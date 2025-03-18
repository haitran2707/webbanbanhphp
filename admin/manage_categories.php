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
    <title>Quản lý Danh Mục</title>
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
    <h2>DANH SÁCH DANH MỤC</h2>
    <table>
        <tr>
            <th>STT</th>
            <th>Mã Danh Mục</th>
            <th>Tên Danh Mục</th>
            <th>Sửa</th>
            <th>Xóa</th>
        </tr>
        <?php
        $result = mysqli_query($connect, "SELECT * FROM danhmuc");
        $stt = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$stt}</td>";
                echo "<td>{$row['MaDM']}</td>";
                echo "<td>{$row['TenDM']}</td>";
                echo "<td><a href='edit_categories.php?id={$row['MaDM']}'>Sửa</a></td>";
                echo "<td><a href='delete_categories.php?id={$row['MaDM']}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a></td>";
                echo "</tr>";
                $stt++;
            }
        } else {
            echo "<tr><td colspan='5' align='center'>Không có dữ liệu</td></tr>";
        }
        ?>
        <tr>
            <td colspan="5" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='add_categories.php'">Thêm mới</button>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='index.php'">Trang chủ</button>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='xuat_dm.php'">Xuất Excel</button>
            </td>
        </tr>
        <tr>
    <td colspan="10" style="text-align: left; padding: 20px;">
        <form action="nhap_dm.php" method="post" enctype="multipart/form-data" style="display: inline-block;">
            <input type="file" name="file" accept=".csv">
            <button type="submit" name="import">Nhập dữ liệu</button>
        </form>
    </tr>
    </table>

    <footer>
        <p>© 2025 Quản trị hệ thống Tam Thái Tử Bakery</p>
    </footer>
</body>
</html>