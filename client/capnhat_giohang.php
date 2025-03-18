<?php
session_start();
include_once("connect.php"); 

if (!isset($_SESSION['MaND'])) {
    header("Location: dangnhap.php"); // Chuyển hướng nếu chưa đăng nhập
    exit();
}

$MaND = $_SESSION['MaND']; // Lấy ID người dùng

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['index']) && !empty($_POST['SoLuong'])) {
        foreach ($_POST['index'] as $key => $index) {
            $SoLuong = intval($_POST['SoLuong'][$key]);

            if (isset($_SESSION['giohang'][$MaND][$index])) {
                if ($SoLuong > 0) {
                    $_SESSION['giohang'][$MaND][$index]['SoLuong'] = $SoLuong;

                    // Cập nhật số lượng trong database
                    $MaSP = $_SESSION['giohang'][$MaND][$index]['MaSP'];
                    $stmt = $connect->prepare("UPDATE giohang SET SoLuong = ? WHERE MaND = ? AND MaSP = ?");
                    $stmt->bind_param("iii", $SoLuong, $MaND, $MaSP);
                    $stmt->execute();
                } else {
                    // Nếu số lượng = 0, xóa sản phẩm khỏi giỏ hàng
                    unset($_SESSION['giohang'][$MaND][$index]);

                    // Xóa sản phẩm khỏi database
                    $MaSP = $_SESSION['giohang'][$MaND][$index]['MaSP'];
                    $stmt = $connect->prepare("DELETE FROM giohang WHERE MaND = ? AND MaSP = ?");
                    $stmt->bind_param("ii", $MaND, $MaSP);
                    $stmt->execute();
                }
            }
        }
    }

    // Nếu người dùng nhấn "Thanh toán", chuyển hướng sang thanhtoan.php
    if (isset($_POST['checkout'])) {
        header("Location: thanhtoan.php");
        exit();
    }

    // Quay lại giỏ hàng sau khi cập nhật
    header("Location: giohang.php");
    exit();
}
?>