<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

// Kiểm tra nếu có id của đơn hàng cần xóa
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_contacts.php");
    exit();
}

$maLH = $_GET['id'];

// Kiểm tra xem đơn hàng có tồn tại không
$query = "SELECT * FROM thongtinlienhe WHERE MaLH = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("s", $maLH);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Thông tin liên hệ không tồn tại!'); window.location.href='manage_contacts.php';</script>";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nếu người dùng xác nhận xóa
    if (isset($_POST['confirm'])) {
        $deleteQuery = "DELETE FROM thongtinlienhe WHERE MaLH = ?";
        $stmt = $connect->prepare($deleteQuery);
        $stmt->bind_param("s", $maLH);

        if ($stmt->execute()) {
            echo "<script>alert('Xóa thông tin liên hệ thành công!'); window.location.href='manage_contacts.php';</script>";
            exit();
        } else {
            echo "<script>alert('Lỗi khi xóa thông tin!'); window.history.back();</script>";
        }
    } else {
        // Nếu người dùng chọn Hủy, quay lại trang quản lý 
        header("Location: manage_contacts.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Thông Tin Liên Hệ</title>
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

        .user-details {
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
        <h2>Xác nhận xóa thông tin liên hệ</h2>
        <div class="user-details">
            <p><strong>Mã Liên Hệ:</strong> <?= htmlspecialchars($row['MaLH']) ?></p>
        </div>
        <form method="POST">
            <button type="submit" name="confirm" class="btn btn-danger">Xóa</button>
            <button type="submit" name="cancel" class="btn btn-secondary">Hủy</button>
        </form>
    </div>
</body>
</html>