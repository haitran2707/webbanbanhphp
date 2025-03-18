<?php
session_start();
include_once("connect.php");

// Nếu người dùng nhấn nút "Xem" từ `donhang.php`, lấy MaDH từ POST và lưu vào SESSION
if (isset($_POST['MaDH'])) {
    $_SESSION['MaDH'] = $_POST['MaDH'];
}

// Kiểm tra nếu đơn hàng vừa đặt có tồn tại
if (!isset($_SESSION['MaDH'])) {
    echo "<p>Không tìm thấy đơn hàng.</p>";
    exit();
}

$MaDH = $_SESSION['MaDH'];

// Truy vấn lấy thông tin đơn hàng
$query = "SELECT * FROM donhang WHERE MaDH = '$MaDH'";
$result = mysqli_query($connect, $query);
$order = mysqli_fetch_assoc($result);

// Kiểm tra nếu đơn hàng không tồn tại
if (!$order) {
    echo "<p>Đơn hàng không tồn tại.</p>";
    exit();
}

// Truy vấn lấy danh sách sản phẩm trong đơn hàng
$queryCT = "SELECT chitietdonhang.*, sanpham.TenSP 
            FROM chitietdonhang 
            JOIN sanpham ON chitietdonhang.MaSP = sanpham.MaSP 
            WHERE MaDH = '$MaDH'";
$resultCT = mysqli_query($connect, $queryCT);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng</title>
    <link rel="stylesheet" href="css/ctdh.css">
</head>
<body>
<div class="order-container">
    <h2>Chi Tiết Đơn Hàng</h2>
    <p><strong>Mã Đơn Hàng:</strong> <?php echo $order['MaDH']; ?></p>
    <p><strong>Ngày Tạo:</strong> <?php echo $order['NgayTao']; ?></p>
    <p><strong>Phương Thức Thanh Toán:</strong> <?php echo $order['PhuongThucThanhToan']; ?></p>
    <p><strong>Địa Chỉ Giao Hàng:</strong> <?php echo $order['DiaChiGiaoHang']; ?></p>
    <p><strong>Số Điện Thoại:</strong> <?php echo $order['SDT']; ?></p>
    <p><strong>Tổng Tiền:</strong> <?php echo number_format($order['TongTien'], 0, ',', '.'); ?> VNĐ</p>

    <h3>Sản Phẩm Đặt Mua</h3>
    <table border="1" cellspacing="0" cellpadding="10">
        <tr>
            <th>Tên Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Đơn Giá</th>
            <th>Thành Tiền</th>
        </tr>
        <?php while ($item = mysqli_fetch_assoc($resultCT)) { ?>
        <tr>
            <td><?php echo $item['TenSP']; ?></td>
            <td><?php echo $item['SoLuong']; ?></td>
            <td><?php echo number_format($item['DonGia'], 0, ',', '.'); ?> VNĐ</td>
            <td><?php echo number_format($item['SoLuong'] * $item['DonGia'], 0, ',', '.'); ?> VNĐ</td>
        </tr>
        <?php } ?>
    </table>
    <a href="donhang.php">Quay lại</a>
    </div>
</body>
</html>