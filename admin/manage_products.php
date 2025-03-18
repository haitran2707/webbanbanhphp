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
    <title>Quản lý Sản Phẩm</title>
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
    <h2>DANH SÁCH SẢN PHẨM</h2>
    <table>
        <tr>
            <th>STT</th>
            <th>Mã Sản Phẩm</th>
            <th>Sản Phẩm</th>
            <th>Danh Mục</th>
            <th>Mô Tả</th>
            <th>Giá</th>
            <th>Số Lượng</th>
            <th>Hình Ảnh</th>
            <th>Sửa</th>
            <th>Xóa</th>
        </tr>
        <?php
        $result = mysqli_query($connect, "SELECT sanpham.*, danhmuc.TenDM 
            FROM sanpham 
            LEFT JOIN danhmuc ON sanpham.MaDM = danhmuc.MaDM");
        $stt = 1;
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$stt}</td>";
            echo "<td>{$row['MaSP']}</td>";
            echo "<td>{$row['TenSP']}</td>";
            echo "<td>" . (!empty($row['TenDM']) ? $row['TenDM'] : "Chưa có danh mục") . "</td>";
            echo "<td>" . substr($row['MoTa'], 0, 70) . "...</td>";
            echo "<td>" . number_format($row['Gia'], 0, ',', '.') . " đ</td>";
            echo "<td>{$row['SoLuong']}</td>";
            echo "<td><img src='{$row['HinhAnh']}' width='80'></td>";
            echo "<td><a href='edit_products.php?id={$row['MaSP']}'>Sửa</a></td>";
            echo "<td><a href='delete_products.php?id={$row['MaSP']}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a></td>";
            echo "</tr>";
            $stt++;
            }
        } else {
        echo "<tr><td colspan='10' align='center'>Không có dữ liệu</td></tr>";
        }
        ?>
        <tr>
            <td colspan="10" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='add_products.php'">Thêm mới</button>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='index.php'">Trang chủ</button>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: left; padding: 20px;">
                <button onclick="window.location.href='xuat_sp.php'">Xuất Excel</button>
            </td>
        </tr>
        <tr>
    <td colspan="10" style="text-align: left; padding: 20px;">
        <form action="nhap_sp.php" method="post" enctype="multipart/form-data" style="display: inline-block;">
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