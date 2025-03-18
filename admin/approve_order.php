<?php
include_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['MaDH'], $_POST['TrangThai'])) {
    $MaDH = mysqli_real_escape_string($connect, $_POST['MaDH']);
    $TrangThai = mysqli_real_escape_string($connect, $_POST['TrangThai']);

    $query = "UPDATE donhang SET TrangThai = '$TrangThai' WHERE MaDH = '$MaDH'";
    if (mysqli_query($connect, $query)) {
        echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href='manage_orders.php';</script>";
    } else {
        echo "<script>alert('Lỗi cập nhật đơn hàng!'); window.location.href='manage_orders.php';</script>";
    }
} else {
    echo "<script>alert('Dữ liệu không hợp lệ!'); window.location.href='orders.php';</script>";
}
?>
