<?php
session_start();
include_once("connect.php");

// Kiểm tra nếu người dùng xác nhận thanh toán
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm'])) {
    // Kiểm tra nếu người dùng đã đăng nhập
    if (!isset($_SESSION['MaND'])) {
        echo "<p>Vui lòng đăng nhập trước khi đặt hàng.</p>";
        exit();
    }

    $MaND = $_SESSION['MaND']; // Lấy ID người dùng

    // Kiểm tra nếu giỏ hàng của tài khoản hiện tại có sản phẩm không
    if (!isset($_SESSION['giohang'][$MaND]) || empty($_SESSION['giohang'][$MaND])) {
        echo "<p>Giỏ hàng trống!</p>";
        exit();
    }

    // Kiểm tra phương thức thanh toán
    if (!isset($_POST['PhuongThucThanhToan'])) {
        echo "<p>Vui lòng chọn phương thức thanh toán.</p>";
        exit();
    }

    // Lấy thông tin từ form
    $date = new DateTime("now", new DateTimeZone("Asia/Ho_Chi_Minh"));
    $NgayTao = $date->format("Y-m-d H:i:s");
    $TongTien = 0;
    $PhuongThucThanhToan = mysqli_real_escape_string($connect, $_POST['PhuongThucThanhToan']);
    $DiaChiGiaoHang = mysqli_real_escape_string($connect, $_POST['DiaChiGiaoHang']);
    $SDT = mysqli_real_escape_string($connect, $_POST['SDT']);

    // Tính tổng tiền đơn hàng
    foreach ($_SESSION['giohang'][$MaND] as $item) {
        $TongTien += $item['DonGia'] * $item['SoLuong'];
    }

    // Thêm đơn hàng vào bảng donhang
    $query = "INSERT INTO donhang (MaND, NgayTao, TongTien, PhuongThucThanhToan, DiaChiGiaoHang, SDT) 
              VALUES ('$MaND', '$NgayTao', '$TongTien', '$PhuongThucThanhToan', '$DiaChiGiaoHang', '$SDT')";

    if (mysqli_query($connect, $query)) {
        $MaDH = mysqli_insert_id($connect); // Lấy ID đơn hàng vừa tạo
        $_SESSION['MaDH'] = $MaDH;

        // Thêm sản phẩm vào bảng chitietdonhang
        foreach ($_SESSION['giohang'][$MaND] as $item) {
            $MaSP = $item['MaSP'];
            $SoLuong = $item['SoLuong'];
            $DonGia = number_format(floatval($item['DonGia']), 2, ',', '');

            $queryCT = "INSERT INTO chitietdonhang (MaDH, MaSP, SoLuong, DonGia) 
                        VALUES ('$MaDH', '$MaSP', '$SoLuong', '$DonGia')";
            mysqli_query($connect, $queryCT);
        }

        
        // Chuyển hướng 
        header("Location: chitietdonhang.php");
        exit();

    } else {
        echo "Lỗi: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Thái Tử Bakery</title>
    <link rel="stylesheet" href="css/tt.css">
</head>
<body>
      <!-- Header -->
    <header>
    <div class="top-bar">
            <img src="images/2025.png" alt="Tam Thái Tử Bakery Logo" class="logo">
            <div class="search-container">
            <input type="text" name="keyword" placeholder="Tìm kiếm..." class="search-box">
            <button type="submit" class="search-btn">🔍</button>
            </div>
            <div class="contact">
                <a href="tel:0123456789">📞 0123 456 789</a>
                <a href="tt_lienhe.php">📧 Thông tin liên hệ</a>
                <a href="login.php">👤 Tài khoản</a>
                <a href="giohang.php">🛒 Giỏ hàng </a>
            </div>
        </div>

        <nav>
        <ul>
        <li><a href="trangchu.php">Trang chủ</a></li>
        <li class="dropdown">
            <a href="sanpham.php">Bánh sinh nhật ▼</a>
            <ul class="dropdown-menu">
                <li><a href="sanpham.php">Bánh Gateaux kem tươi </a></li>
                <li><a href="sanpham.php">Bánh Gateaux Mousse</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="sanpham.php">Bánh mì & Bánh mặn ▼</a>
            <ul class="dropdown-menu">
                <li><a href="sanpham.php">Bánh Mì</a></li>
                <li><a href="sanpham.php">Bánh Mặn</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="sanpham.php">Cookies & Minicake ▼</a>
            <ul class="dropdown-menu">
                <li><a href="sanpham.php">Bánh Cookies</a></li>
                <li><a href="sanpham.php">Bánh Minicake</a></li>
            </ul>
        </li>
        <li><a href="tintuc.php">Tin tức</a></li>
        <li class="dropdown">
            <a href="login.php">Đăng xuất ▼</a>
            <ul class="dropdown-menu">
                <li><a href="quen_mk.php">Quên Mật Khẩu?</a></li>
                <li><a href="dangky.php">Đăng ký tài khoản</a></li>
            </ul>
        </li>
        </ul>
        </nav>

    </header>   
    
    <!-- Banner -->
    <section class="banner">
        <img src="https://theme.hstatic.net/1000313040/1000406925/14/ms_banner_img4.jpg?v=2247" alt="Bánh ngon mỗi ngày">
    </section>
            

    <div class="container">
    <!-- Thông tin thanh toán -->
    <div class="card">
    <h2>Thông Tin Thanh Toán</h2>
    <form method="post" action="">
        <div class="info-group">
            <label for="HoTen">Họ Tên</label>
            <input type="text" id="HoTen" name="HoTen" 
                value="<?php 
                    if (isset($_SESSION['MaND'])) {
                        $maND = $_SESSION['MaND'];
                        $query = "SELECT HoTen FROM nguoidung WHERE MaND = '$maND'";
                        $result = mysqli_query($connect, $query);
                        $user = mysqli_fetch_assoc($result);
                        echo htmlspecialchars($user['HoTen']);
                    }
                ?>" required>
        </div>
        <div class="info-group">
            <label for="SDT">Số điện thoại</label>
            <input type="text" id="SDT" name="SDT" required>
        </div>
        <div class="info-group">
            <label for="DiaChiGiaoHang">Địa chỉ nhận hàng</label>
            <input type="text" id="DiaChiGiaoHang" name="DiaChiGiaoHang" required>
        </div>

        <!-- Đơn đặt hàng -->
        <div class="card">
            <h2>Đơn Đặt Hàng</h2>
            <table class="order-table">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                </tr>
                <?php
                $MaND = $_SESSION['MaND'] ?? null; // Lấy MaND từ session
                $TongTien = 0;
                if (isset($_SESSION['MaND']) && !empty($_SESSION['giohang'][$MaND])) {
                    foreach ($_SESSION['giohang'][$MaND] as $item) {
                        $tongGia = $item['DonGia'] * $item['SoLuong'];
                        $TongTien += $tongGia;
                        echo "<tr>
                            <td>{$item['TenSP']}</td>
                            <td>" . number_format($item['DonGia'], 0, ',', '.') . "₫</td>
                            <td>{$item['SoLuong']}</td>
                            <td>" . number_format($tongGia, 0, ',', '.') . "₫</td>
                        </tr>";
                    }
                }else {
                    echo "<tr><td colspan='4'>Giỏ hàng trống!</td></tr>";
                }
                ?>
                <tr>
                    <td colspan="3" class="total">Tổng tiền thanh toán:</td>
                    <td class="total"><?php echo number_format($TongTien ?: 0, 0, ',', '.') . "₫"; ?></td>
                </tr>
            </table>

            <!-- Phương thức thanh toán -->
            <div class="payment-methods">
                <label>Phương thức thanh toán</label>
                <input type="radio" id="cod" name="PhuongThucThanhToan" value="cod" required>
                <label for="cod">Thanh toán khi nhận hàng</label><br>

                <input type="radio" id="momo" name="PhuongThucThanhToan" value="momo">
                <label for="momo">Ví điện tử Momo</label>
            </div>

            <!-- Nút xác nhận thanh toán -->
            <button type="submit" class="button" name="confirm">Xác nhận thanh toán</button>
            </div>
        </form> 
    </div>
    </div>


    <!-- Footer -->
    <footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>ĐỊA CHỈ</h3>
            <p>📍 Số 09 Trần Thái Tông, Q. Cầu Giấy, Hà Nội</p>
            <p>📅 Ngày khai trương: 21/07/2010</p>
        </div>

        <div class="footer-section">
            <h3>CHÍNH SÁCH</h3>
            <ul>
                <li><a href="#">Chính sách và quy định chung</a></li>
                <li><a href="#">Chính sách giao dịch, thanh toán</a></li>
                <li><a href="#">Chính sách đổi trả</a></li>
                <li><a href="#">Chính sách bảo mật</a></li>
                <li><a href="#">Chính sách vận chuyển</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>THÔNG TIN CÔNG TY</h3>
            <p>🌐 <strong>tamthaitubakery.vn</strong></p>
            <p>📞 Hotline: 0123 456 789</p>
            <p>🏢 Trụ sở: Số 09 Trần Thái Tông, Cầu Giấy, Hà Nội</p>
            <p>🌏 Quốc gia: Việt Nam</p>
            <div class="social-icons">
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/128/5968/5968764.png" alt="Facebook"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/128/4138/4138124.png" alt="Instagram"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/128/5968/5968534.png" alt="Gmail"></a>
            </div>
        </div>

        <div class="footer-section">
            <img src="https://theme.hstatic.net/1000313040/1000406925/14/hg_img1.png?v=2247" width="170" alt="Bộ Công Thương">
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2025 Tam Thái Tử Bakery. All rights reserved.</p>
    </div>
</footer>
</body>
</html>