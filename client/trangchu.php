<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Thái Tử Bakery</title>
    <link rel="stylesheet" href="css/home.css">
    <!-- Thư viện Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Thư viện Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
    <div class="top-bar">
            <a href="trangchu.php"><img src="images/2025.png" alt="Tam Thái Tử Bakery Logo" class="logo"></a>
            <div class="search-container">
            <form action="sanpham.php" method="GET" class="search-form">
                <input type="text" name="keyword" placeholder="Tìm kiếm..." class="search-box">
            <button type="submit" class="search-btn">🔍</button>
            </form>
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
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="https://theme.hstatic.net/1000313040/1000406925/14/ms_banner_img4.jpg?v=2247" alt="Bánh ngon mỗi ngày">
            </div>
            <div class="swiper-slide">
                <img src="https://theme.hstatic.net/1000313040/1000406925/14/ms_banner_img3.jpg?v=2247" alt="Bánh kem tươi">
            </div>
            <div class="swiper-slide">
                <img src="https://theme.hstatic.net/1000313040/1000406925/14/ms_banner_img2.jpg?v=2247" alt="Bánh sinh nhật đẹp">
            </div>
        </div>
        <!-- Nút điều hướng -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
    </section>

    <script>
    var swiper = new Swiper(".mySwiper", {
        loop: true, // Cho phép lặp lại ảnh
        autoplay: {
            delay: 3000, // Chuyển ảnh sau 3 giây
            disableOnInteraction: false, // Dừng khi người dùng thao tác
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
    </script>

    <!-- Danh sách sp -->
    <section class="product-list">
    <h2>CÁC SẢN PHẨM</h2>
    <div class="products">
    <?php
    include_once("connect.php"); 
        if ($connect) {
            $query = "SELECT MaSP, TenSP, MoTa, Gia, SoLuong, HinhAnh FROM sanpham";
            $result = mysqli_query($connect, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product'>";
                    echo "<img src='{$row['HinhAnh']}' alt='{$row['TenSP']}'>";
                    echo "<h3>{$row['TenSP']}</h3>";
                    echo "<p class='price'>" . number_format($row['Gia'], 0, ',', '.') . " đ</p>";
                    echo "<a href='chitietsp.php?id=" . urlencode($row['MaSP']) . "' class='buy-button'> <button>🛒 Xem chi tiết</button></a>";
                    echo "</div>";
                }
            } else {
                echo "<p>Không có sản phẩm nào!</p>";
            }
        } else {
            echo "<p>Lỗi kết nối cơ sở dữ liệu!</p>";
        }
    ?>
    </div>   
    </section>
 
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