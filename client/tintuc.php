<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Thái Tử Bakery</title>
    <link rel="stylesheet" href="css/tintuc.css">
</head>
<body>
    <!-- Header -->
    <header>
    <div class="top-bar">
    <a href="trangchu.php"><img src="images/2025.png" alt="Tam Thái Tử Bakery Logo" class="logo"></a>
            <div class="search-container">
            <input type="text" name="keyword" placeholder="Tìm kiếm..." class="search-box">
            <button type="submit">
                <img src="https://cdn-icons-png.flaticon.com/128/954/954591.png" alt="Search">
            </button>
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

 
    <div class="news-container">
    <?php
    include 'connect.php'; // Kết nối database

    // Số bài viết hiển thị trên mỗi trang
    $limit = 3;

    // Xác định trang hiện tại (nếu không có thì mặc định là 1)
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }

    // Tính offset (bỏ qua bao nhiêu bản ghi)
    $offset = ($page - 1) * $limit;

    // Đếm tổng số bài viết để tính tổng số trang
    $total_query = "SELECT COUNT(*) AS total FROM tintuc";
    $total_result = mysqli_query($connect, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_records = $total_row['total'];
    $total_pages = ceil($total_records / $limit);

    // Truy vấn dữ liệu từ bảng tintuc kết hợp với bảng nguoidung để lấy tên người đăng
    $query = "SELECT tintuc.*, nguoidung.HoTen AS TenNguoiDung 
              FROM tintuc
              INNER JOIN nguoidung ON tintuc.MaND = nguoidung.MaND
              ORDER BY tintuc.NgayDang DESC
              LIMIT $limit OFFSET $offset";

    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='news-card'>";
            echo "<img src='{$row['HinhAnh']}' alt='Hình ảnh bài viết' >";
            echo "<div class='news-card-content'>";
            echo "<h3 class='news-card-title'>{$row['TieuDe']}</h3>";
            echo "<div class='news-card-meta'>";
            echo "<span class='date'>" . date('d/m/Y H:i', strtotime($row['NgayDang'])) . "</span>";
            echo "<span class='author'>Tác giả: {$row['TenNguoiDung']}</span>";
            echo "</div>";
            echo "<p class='news-card-description'>" . nl2br($row['NoiDung']) . "</p>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>Không có bài viết nào.</p>";
    }
    mysqli_close($connect);
    ?>
    </div>

    <!-- Phân trang -->
    <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>">«</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>">»</a>
    <?php endif; ?>
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