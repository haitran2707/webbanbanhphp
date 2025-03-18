<?php
session_start();
include_once("connect.php");

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['MaND'])) {
    echo "Vui lòng đăng nhập trước khi thêm sản phẩm vào giỏ hàng.";
    exit();
}

$MaND = $_SESSION['MaND']; // Lấy ID người dùng

// Nếu giỏ hàng chưa có trong session, tạo giỏ hàng mới
if (!isset($_SESSION['giohang'][$MaND])) {
    $_SESSION['giohang'][$MaND] = [];
}

// Kiểm tra nếu người dùng thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    $MaSP = $_POST['MaSP'];
    $TenSP = $_POST['TenSP'];
    $Gia = floatval($_POST['Gia']);
    $HinhAnh = $_POST['HinhAnh'];
    $SoLuong = max(1, intval($_POST['SoLuong'])); // Đảm bảo số lượng tối thiểu là 1

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $found = false;
    foreach ($_SESSION['giohang'][$MaND] as &$item) {
        if ($item['MaSP'] === $MaSP) {
            $item['SoLuong'] += $SoLuong; // Cộng dồn số lượng
            $found = true;
            break;
        }
    }

    // Nếu sản phẩm chưa có trong giỏ, thêm mới vào session
    if (!$found) {
        $_SESSION['giohang'][$MaND][] = [
            'MaSP' => $MaSP,
            'TenSP' => $TenSP,
            'DonGia' => $Gia,
            'HinhAnh' => $HinhAnh,
            'SoLuong' => $SoLuong
        ];
    }

    // Thêm hoặc cập nhật vào database
    // Kiểm tra sản phẩm đã có trong bảng giohang chưa
    $check_query = "SELECT * FROM giohang WHERE MaND = '$MaND' AND MaSP = '$MaSP'";
    $result = mysqli_query($connect, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Nếu đã tồn tại, cập nhật số lượng
        $update_query = "UPDATE giohang SET SoLuong = SoLuong + $SoLuong WHERE MaND = '$MaND' AND MaSP = '$MaSP'";
        mysqli_query($connect, $update_query);
    } else {
        // Nếu chưa tồn tại, thêm mới vào database
        $insert_query = "INSERT INTO giohang (MaND, MaSP, SoLuong, DonGia, NgayTao) 
                         VALUES ('$MaND', '$MaSP', '$SoLuong', '$Gia', NOW())";
        mysqli_query($connect, $insert_query);
    }

    // Chuyển hướng để tránh gửi lại form khi load lại trang
    header("Location: giohang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Thái Tử Bakery</title>
    <link rel="stylesheet" href="css/giohang.css">
</head>
<body>
    <!-- Header -->
    <header>
    <div class="top-bar">
    <a href="trangchu.php"><img src="images/2025.png" alt="Tam Thái Tử Bakery Logo" class="logo"></a>
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
        <li><a href="donhang.php">Đơn hàng</a></li>
        </ul>
        </nav>
    </header>


    <!-- Banner -->
    <section class="banner">
        <img src="https://theme.hstatic.net/1000313040/1000406925/14/ms_banner_img4.jpg?v=2247" alt="Bánh ngon mỗi ngày">
    </section>

    <!-- Giỏ hàng -->
    <div class="cart-container">
    <h4>Giỏ Hàng</h4>
    <table>
        <thead>
            <tr>
                <th>Thông tin chi tiết sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Tổng giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $tongTien = 0;
        // Kiểm tra nếu người dùng đã đăng nhập
        if (!isset($_SESSION['MaND'])) {
            echo "<tr><td colspan='5'>Vui lòng đăng nhập để xem giỏ hàng.</td></tr>";
            exit();
        }
        $MaND = $_SESSION['MaND']; // Lấy ID người dùng

        if (!empty($_SESSION['giohang'][$MaND])) {
            echo "<form method='POST' action='capnhat_giohang.php'>"; // Bọc toàn bộ giỏ hàng trong form
            foreach ($_SESSION['giohang'][$MaND] as $index => $item) {
                $tongGia = $item['DonGia'] * $item['SoLuong'];
                $tongTien += $tongGia;
            echo "<tr>
            <td>
                <img src='{$item['HinhAnh']}' alt='{$item['TenSP']}' class='product-img'>
                <p><strong>{$item['TenSP']}</strong></p>
            </td>
            <td>" . number_format($item['DonGia'], 0, ',', '.') . "₫</td>
            <td>
                <input type='hidden' name='index[]' value='{$index}'>
                <input type='number' name='SoLuong[]' value='{$item['SoLuong']}' min='1' style='width: 60px;'>
            </td>
            <td>" . number_format($tongGia, 0, ',', '.') . "₫</td>
            <td><a href='xoa_giohang.php?index={$index}' class='text-danger'>Xóa</a></td>
            </tr>";
        }
        echo "
            </tbody>
        </table>

        <p><strong>Tổng tiền: </strong>" . number_format($tongTien, 0, ',', '.') . "₫</p>

        <div style='display: flex; justify-content: flex-end; gap: 10px;'>
            <button type='submit' name='update_cart' class='btn btn-update'>Cập nhật</button>
            <button type='submit' name='checkout' class='btn btn-checkout'>Thanh toán</button>
        </div>
        </form>";
    } else {
        echo "<tr><td colspan='5'>Giỏ hàng trống!</td></tr></tbody></table>";
    }
    ?>
        </tbody>
    </table>
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
                <a href="https://www.facebook.com/"><img src="https://cdn-icons-png.flaticon.com/128/5968/5968764.png" alt="Facebook"></a>
                <a href="https://www.bing.com/ck/a?!&&p=81cd7732c099218c0380fe526f5e294143d6a5686920e36600d6b2b974c38122JmltdHM9MTc0MDk2MDAwMA&ptn=3&ver=2&hsh=4&fclid=174767a2-8293-6f0c-31b4-75ee83816e8e&psq=instagram&u=a1aHR0cHM6Ly93d3cuaW5zdGFncmFtLmNvbS8&ntb=1"><img src="https://cdn-icons-png.flaticon.com/128/4138/4138124.png" alt="Instagram"></a>
                <a href="https://mail.google.com/mail/u/0/#inbox"><img src="https://cdn-icons-png.flaticon.com/128/5968/5968534.png" alt="Gmail"></a>
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