<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

// Kiểm tra nếu có id của  đơn hàng cần xóa
if (!isset($_GET['id']) || empty($_GET['sp'])) {
    header("Location: manage_orders_details.php");
    exit();
}

$maDH = $_GET['id'];

// Kiểm tra xem đơn hàng có tồn tại không
$query = "SELECT donhang.*, nguoidung.HoTen AS HoTenKhachHang FROM donhang 
          INNER JOIN nguoidung ON donhang.MaND = nguoidung.MaND 
          WHERE MaDH = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("s", $maDH);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Đơn hàng không tồn tại!'); window.location.href='manage_orders_details.php';</script>";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirm'])) {
        // Xóa chi tiết đơn hàng trước
        $deleteDetailsQuery = "DELETE FROM chitietdonhang WHERE MaDH = ?";
        $stmt = $connect->prepare($deleteDetailsQuery);
        $stmt->bind_param("s", $maDH);
        $stmt->execute();

        // Xóa đơn hàng chính
        $deleteOrderQuery = "DELETE FROM donhang WHERE MaDH = ?";
        $stmt = $connect->prepare($deleteOrderQuery);
        $stmt->bind_param("s", $maDH);

        if ($stmt->execute()) {
            echo "<script>alert('Xóa đơn hàng thành công!'); window.location.href='manage_orders_details.php';</script>";
            exit();
        } else {
            echo "<script>alert('Lỗi khi xóa đơn hàng!'); window.history.back();</script>";
        }
    } else {
        // Nếu người dùng chọn Hủy, quay lại trang quản lý đơn hàng
        header("Location: manage_orders.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Chi Tiết Đơn Hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 50px;
        }

        .container {
            width: 50%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h2 {
            color: #dc3545;
        }

        .order-details {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }

        p {
            font-size: 16px;
            color: #333;
        }

        .btn {
            padding: 10px 15px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Xác nhận xóa chi tiết đơn hàng</h2>
        <div class="order-details">
            <p><strong>Mã Đơn Hàng:</strong> <?= htmlspecialchars($row['MaDH']) ?></p>
        </div>
        <form method="POST">
            <button type="submit" name="confirm" class="btn btn-danger">Xóa</button>
            <button type="submit" name="cancel" class="btn btn-secondary">Hủy</button>
        </form>
    </div>
</body>
</html>