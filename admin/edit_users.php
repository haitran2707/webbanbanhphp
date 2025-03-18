<?php
session_start();
// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include_once("connect.php");

// Kiểm tra id người dùng hợp lệ
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$maND = $_GET['id'];

// Kiểm tra nếu Admin đang cố chỉnh sửa tài khoản khác
if ($_SESSION['MaND'] != $maND) {
    echo "<script>alert('Bạn chỉ có thể chỉnh sửa tài khoản của chính mình!'); window.location.href='manage_users.php';</script>";
    exit();
}

// Sử dụng Prepared Statement để tránh SQL Injection
$stmt = $connect->prepare("SELECT * FROM nguoidung WHERE MaND = ?");
$stmt->bind_param("s", $maND);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Người dùng không tồn tại!'); window.location.href='manage_users.php';</script>";
    exit();
}

$row = $result->fetch_assoc();
$stmt->close();

// Khi người dùng gửi form cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hoTen = trim($_POST['HoTen']);
    $email = trim($_POST['Email']);
    $sdt = trim($_POST['SDT']);
    $diaChi = trim($_POST['DiaChi']);
    $matkhau = trim($_POST['MatKhau']);
    $quyenND = trim($_POST['QuyenND']);

    // Kiểm tra dữ liệu đầu vào không rỗng
    if (empty($hoTen) || empty($email) || empty($sdt) || empty($diaChi) || empty($quyenND)) {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.location.href='edit_users.php?id=$maND';</script>";
        exit();
    }

    // Nếu không nhập mật khẩu mới, giữ nguyên mật khẩu cũ
    if (!empty($matkhau)) {
        $updateQuery = "UPDATE nguoidung SET HoTen=?, Email=?, SDT=?, DiaChi=?, MatKhau=?, QuyenND=? WHERE MaND=?";
        $stmt = $connect->prepare($updateQuery);
        $stmt->bind_param("sssssss", $hoTen, $email, $sdt, $diaChi, $matkhau, $quyenND, $maND);
    } else {
        $updateQuery = "UPDATE nguoidung SET HoTen=?, Email=?, SDT=?, DiaChi=?, QuyenND=? WHERE MaND=?";
        $stmt = $connect->prepare($updateQuery);
        $stmt->bind_param("ssssss", $hoTen, $email, $sdt, $diaChi, $quyenND, $maND);
    }

    // Thực thi truy vấn
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật người dùng thành công!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "Lỗi cập nhật: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa người dùng</title>
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
        <h2>Chỉnh sửa người dùng</h2>
        <form action="" method="POST">
            <label for="HoTen">Họ Tên:</label>
            <input type="text" name="HoTen" value="<?php echo $row['HoTen']; ?>" required>

            <label for="Email">Email:</label>
            <input type="email" name="Email" value="<?php echo $row['Email']; ?>" required>

            <label for="SDT">Số Điện Thoại:</label>
            <input type="text" name="SDT" value="<?php echo $row['SDT']; ?>" required>

            <label for="DiaChi">Địa Chỉ:</label>
            <input type="text" name="DiaChi" value="<?php echo $row['DiaChi']; ?>" required>

            <label for="MatKhau">Mật Khẩu:</label>
            <input type="text" name="MatKhau" value="<?php echo $row['MatKhau']; ?>" required>

            <label for="QuyenND">Quyền Người Dùng:</label>
            <input type="text" name="QuyenND" value="<?php echo $row['QuyenND']; ?>" required>

            <button type="submit" class="update-btn">Cập Nhật</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='manage_users.php'">Hủy</button>
        </form>
    </div>
</body>
</html>