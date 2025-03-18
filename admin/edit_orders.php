<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

$maDH = $_GET['id'];

$query = "SELECT * FROM donhang WHERE MaDH = '$maDH'";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Đơn hàng không tồn tại!'); window.location.href='manage_orders.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trangThai = mysqli_real_escape_string($connect, $_POST['TrangThai']);
    $tongTien = mysqli_real_escape_string($connect, $_POST['TongTien']);
    $diaChiGiaoHang = mysqli_real_escape_string($connect, $_POST['DiaChiGiaoHang']);
    $phuongThucThanhToan = mysqli_real_escape_string($connect, $_POST['PhuongThucThanhToan']);

    $updateQuery = "UPDATE donhang SET TrangThai='$trangThai', TongTien='$tongTien', DiaChiGiaoHang='$diaChiGiaoHang', PhuongThucThanhToan= '$phuongThucThanhToan' WHERE MaDH='$maDH'";

    if (mysqli_query($connect, $updateQuery)) {
        echo "<script>alert('Cập nhật đơn hàng thành công!'); window.location.href='manage_orders.php';</script>";
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
    <title>Chỉnh sửa đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
        }
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
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .update-btn {
            background-color: #4CAF50;
            color: white;
        }
        .update-btn:hover {
            background-color: #45a049;
        }
        .cancel-btn {
            background-color: #d9534f;
            color: white;
        }
        .cancel-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chỉnh sửa đơn hàng</h2>
        <form action="" method="POST">
            <label for="TrangThai">Trạng Thái:</label>
            <input type="text" name="TrangThai" value="<?php echo $row['TrangThai']; ?>" required>

            <label for="TongTien">Tổng Tiền:</label>
            <input type="number" name="TongTien" value="<?php echo $row['TongTien']; ?>" required>

            <label for="DiaChiGiaoHang">Địa Chỉ Giao Hàng:</label>
            <textarea name="DiaChiGiaoHang" required><?php echo $row['DiaChiGiaoHang']; ?></textarea>

            <label for="PhuongThucThanhToan">Trạng Thái:</label>
            <input type="text" name="PhuongThucThanhToan" value="<?php echo $row['PhuongThucThanhToan']; ?>" required>

            <button type="submit" class="update-btn">Cập Nhật</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='manage_orders.php'">Hủy</button>
        </form>
    </div>
</body>
</html>
