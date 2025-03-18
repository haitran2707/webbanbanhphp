<?php
session_start();
include_once("connect.php"); 

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['MaND'])) {
    echo "Vui lòng đăng nhập để thực hiện thao tác này.";
    exit();
}

$MaND = $_SESSION['MaND']; // Lấy ID người dùng

// Kiểm tra nếu tham số 'index' tồn tại
if (isset($_GET['index'])) {
    $index = intval($_GET['index']); // Đảm bảo index là số nguyên

    // Kiểm tra nếu giỏ hàng tồn tại
    if (!isset($_SESSION['giohang'][$MaND])) {
        echo "Giỏ hàng không tồn tại.";
        exit();
    }

    // Kiểm tra nếu index hợp lệ
    if (isset($_SESSION['giohang'][$MaND][$index])) {
        $MaSP = $_SESSION['giohang'][$MaND][$index]['MaSP']; // Lấy mã sản phẩm

        // Xóa sản phẩm khỏi database
        $stmt = $connect->prepare("DELETE FROM giohang WHERE MaND = ? AND MaSP = ?");
        $stmt->bind_param("ii", $MaND, $MaSP);
        if ($stmt->execute()) {
            // Xóa sản phẩm trong session
            unset($_SESSION['giohang'][$MaND][$index]);

            // Sắp xếp lại mảng giỏ hàng
            $_SESSION['giohang'][$MaND] = array_values($_SESSION['giohang'][$MaND]);

            // Chuyển hướng về giỏ hàng
            header("Location: giohang.php");
            exit();
        } else {
            echo "Lỗi khi xóa sản phẩm khỏi database!";
        }
    } else {
        echo "Sản phẩm không tồn tại trong giỏ hàng.";
    }
} else {
    echo "Không có sản phẩm để xóa.";
}
?>