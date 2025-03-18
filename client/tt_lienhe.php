<?php
session_start();
include 'connect.php'; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($connect, $_POST['Email']);
    $sdt = mysqli_real_escape_string($connect, $_POST['Sdt']);
    $diaChi = mysqli_real_escape_string($connect, $_POST['DiaChi']);
    $noiDung = mysqli_real_escape_string($connect, $_POST['NoiDung']);

    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (isset($_SESSION['MaND'])) {
        $MaND = $_SESSION['MaND'];

        // Thêm dữ liệu vào bảng thongtinlienhe
        $query = "INSERT INTO thongtinlienhe (MaND, Email, SDT, DiaChi, NoiDung) 
                  VALUES ('$MaND','$email', '$sdt', '$diaChi', '$noiDung')";

        if (mysqli_query($connect, $query)) {
            echo "<script>alert('Gửi thông tin liên hệ thành công!'); window.location.href='tt_lienhe.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi gửi thông tin!'); window.location.href='tt_lienhe.php';</script>";
        }
    } else {
        echo "<script>alert('Vui lòng đăng nhập để gửi liên hệ!'); window.location.href='tt_lienhe.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Thái Tử Bakery</title>
    <link rel="stylesheet" href="css/ttlh.css">
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

    <!-- Nội dung chính -->
    <main class="contact-container">
        <div class="contact-info">
            <h2>Thông tin liên hệ</h2>
            <p><strong>Hotline:</strong> (+84) 0366-77-00-88</p>
            <p><strong>Email:</strong> tamtt2010@gmail.com</p>
            <p><strong>Địa chỉ:</strong> Số 09 Trần Thái Tông, Cầu Giấy, Hà Nội</p>

            <h3>Đặt câu hỏi thắc mắc?</h3>
            <form action="" method="post">

                <label for="Email">Email</label>
                <input type="Email" name="Email" required>

                <label for="Sdt">Số điện thoại</label>
                <input type="text" name="Sdt" required>

                <label for="DiaChi">Địa chỉ</label>
                <input type="text" name="DiaChi" required>

                <label for="NoiDung">Nội dung</label>
                <textarea  name="NoiDung" rows="5" required></textarea>

                <button type="submit">Gửi liên hệ</button>
            </form>
        </div>

        <!-- Google Map -->
        <div class="map-container">
            <h2>Vị trí của chúng tôi</h2>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.637064541317!2d106.67584897593205!3d10.762663459309488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38c74b4fbd%3A0x79b7129a47d3b19a!2zMTIzIMSQLiBBQkMsIFF14bqtbiAxLCBUUC5IQ00!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </main>
 
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