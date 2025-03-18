<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_contacts.php");
    exit();
}

$maLH = $_GET['id'];

$query = "SELECT * FROM thongtinlienhe WHERE MaLH = '$maLH'";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Thông tin liên hệ không tồn tại!'); window.location.href='manage_contacts.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($connect, $_POST['Email']);
    $sdt = mysqli_real_escape_string($connect, $_POST['SDT']);
    $diaChi = mysqli_real_escape_string($connect, $_POST['DiaChi']);
    $noiDung = mysqli_real_escape_string($connect, $_POST['NoiDung']);
    $ngayGui = mysqli_real_escape_string($connect, $_POST['NgayGui']);

    $updateQuery = "UPDATE thongtinlienhe SET Email='$email', SDT='$sdt', DiaChi='$diaChi', NoiDung='$noiDung', NgayGui='$ngayGui' WHERE MaLH='$maLH'";

    if (mysqli_query($connect, $updateQuery)) {
        echo "<script>alert('Cập nhật thông tin liên hệ thành công!'); window.location.href='manage_contacts.php';</script>";
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
    <title>Chỉnh sửa thông tin liên hệ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
        input {
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
        .cancel-btn {
            background-color: #d9534f;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chỉnh sửa thông tin liên hệ</h2>
        <form action="" method="POST">

            <label for="SDT">Số Điện Thoại:</label>
            <input type="text" name="SDT" value="<?php echo $row['SDT']; ?>" required>

            <label for="Email">Email:</label>
            <input type="text" name="Email" value="<?php echo $row['Email']; ?>" required>

            <label for="NoiDung">Nội Dung:</label>
            <input type="text" name="NoiDung" value="<?php echo $row['NoiDung']; ?>" required>

            <label for="DiaChi">Địa Chỉ:</label>
            <input type="text" name="DiaChi" value="<?php echo $row['DiaChi']; ?>" required>

            <label for="NgayGui">Ngày Gửi:</label>
            <input type="datetime-local" name="NgayGui" value="<?php echo $row['NgayGui']; ?>" required>

            <button type="submit" class="update-btn">Cập Nhật</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='manage_users.php'">Hủy</button>
        </form>
    </div>
</body>
</html>