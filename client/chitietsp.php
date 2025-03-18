<?php
include 'connect.php';

// Kiểm tra nếu tham số MaSP được truyền
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Lỗi: Mã sản phẩm không hợp lệ hoặc bị thiếu!");
}

$maSP = $_GET['id']; // Không ép kiểu số nguyên vì MaSP có thể chứa ký tự (SP001, SP002)

// Truy vấn sản phẩm theo MaSP
$sql = "SELECT * FROM sanpham WHERE MaSP = ?";
$stmt = $connect->prepare($sql);
if (!$stmt) {
    die("Lỗi truy vấn: " . $connect->error);
}

$stmt->bind_param("s", $maSP); // Sử dụng "s" thay vì "i" vì MaSP là kiểu chuỗi
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Không tìm thấy sản phẩm trong database!");
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Thái Tử Bakery</title>
    <link rel="stylesheet" href="css/ctsp.css">
    <link rel="stylesheet" href="css/danhgia.css">
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
        <li><a href="donhang.php">Đơn hàng</a></li>
        </ul>
        </nav>

    </header>

    <!-- Banner -->
    <section class="banner">
        <img src="https://theme.hstatic.net/1000313040/1000406925/14/ms_banner_img4.jpg?v=2247" alt="Bánh ngon mỗi ngày">
    </section>

    <div class="container">
<!-- Thông tin sản phẩm -->
<div class="product-container">
    <div class="product-gallery">
        <img src="<?php echo htmlspecialchars($row['HinhAnh']); ?>" alt="<?php echo htmlspecialchars($row['TenSP']); ?>">
    </div>

    <div class="product-info">
        <h2><?php echo htmlspecialchars($row['TenSP']); ?></h2>
        <p><strong>Mã sản phẩm:</strong> <?php echo htmlspecialchars($row['MaSP']); ?></p>
        <p class="price"><?php echo number_format($row['Gia'], 0, ',', '.') . "₫"; ?></p>
        <p><strong>Số lượng còn:</strong> <?php echo htmlspecialchars($row['SoLuong']); ?></p>

        <?php $maxQuantity = max(1, intval($row['SoLuong'])); ?>
        <div class="quantity-container">
            <span>Số lượng:</span>
            <input type="number"  name="SoLuong" value="1" min="1" max="<?php echo $maxQuantity; ?>">
        </div>

        <div class="buy-buttons">
<!-- Form Thêm vào giỏ hàng -->
<form action="giohang.php" class="add-to-cart" method="POST">
    <input type="hidden" name="MaSP" value="<?php echo htmlspecialchars($row['MaSP']); ?>">
    <input type="hidden" name="TenSP" value="<?php echo htmlspecialchars($row['TenSP']); ?>">
    <input type="hidden" name="Gia" value="<?php echo htmlspecialchars($row['Gia']); ?>">
    <input type="hidden" name="SoLuong" value="1">
    <input type="hidden" name="HinhAnh" value="<?php echo htmlspecialchars($row['HinhAnh']); ?>">
    <button type="submit" name="add_to_cart" class="add-to-cart-btn">Thêm vào giỏ hàng</button>
</form>

<!-- Form Mua ngay -->
<form action="thanhtoan.php" method="POST">
    <input type="hidden" name="MaSP" value="<?php echo htmlspecialchars($row['MaSP']); ?>">
    <button type="submit" class="buy-now-btn">Mua ngay</button>
</form>
</div>
    </div>
</div>

<!-- Mô tả sản phẩm -->
<div class="product-details">
    <div class="tabs">
        <button class="tab-button active" onclick="openTab(event, 'mo-ta')">Mô tả chung</button>
    </div>
    
    <div id="mo-ta" class="tab-content active">
        <p><?php echo nl2br(htmlspecialchars($row['MoTa'])); ?></p>
    </div>

</div>

<?php 
// Nhúng file xử lý đánh giá
include 'binhluan.php'; 
?>
<div class="review-container">
    <!-- Tiêu đề đánh giá -->
    <h2 class="review-title">Đánh giá sản phẩm</h2>

    <!-- Điểm đánh giá tổng quát -->
    <div class="review-score">
        <?php echo htmlspecialchars($averageRating, ENT_QUOTES, 'UTF-8'); ?> <small>/ 5</small>
    </div>

    <!-- Thanh đánh giá sao -->
    <?php for ($i = 5; $i >= 1; $i--) : ?>
        <div class="rating-bar">
            <span><?php echo $i; ?> ★</span>
            <div class="bar">
                <div class="fill" style="width: <?php echo htmlspecialchars($percentages[$i], ENT_QUOTES, 'UTF-8'); ?>%;"></div>
            </div>
            <span><?php echo htmlspecialchars($percentages[$i], ENT_QUOTES, 'UTF-8'); ?>%</span>
        </div>
    <?php endfor; ?>

    <!-- Danh sách đánh giá -->
    <div class="review-list">
        <?php
        if ($totalReviews > 0) {
            while ($row = $result->fetch_assoc()) {
                $stars = str_repeat("★", $row['SoSao']) . str_repeat("☆", 5 - $row['SoSao']); // Hiển thị sao
                echo "<div class='review-item'>";
                echo "<div class='review-user'>";
                echo "<img src='https://cdn-icons-png.flaticon.com/128/149/149071.png' alt='User'> " . htmlspecialchars($row['HoTen'], ENT_QUOTES, 'UTF-8') . " <span style='color: green;'>✔ Đã mua tại Tam Thái Tử Bakery</span>";
                echo "</div>";
                echo "<div class='review-stars'>$stars</div>";
                echo "<p class='review-text'>" . htmlspecialchars($row['BinhLuan'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<div class='review-actions'>";
                echo "<span>Ngày đánh giá: " . htmlspecialchars($row['NgayDanhGia'], ENT_QUOTES, 'UTF-8') . "</span>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='review-text'>Chưa có đánh giá nào cho sản phẩm này.</p>";
        }
        ?>
    </div>
    
    <div class="review-form" style="display: none;">
    <h3>Viết đánh giá của bạn</h3>
    <form id="reviewForm">
        <input type="hidden" name="masp" value="<?php echo $maSP; ?>">
        <label>Họ tên:</label>
        <input type="text" name="hoten" required>
        
        <label>Chọn số sao:</label>
        <select name="sosao">
            <option value="5">5 sao</option>
            <option value="4">4 sao</option>
            <option value="3">3 sao</option>
            <option value="2">2 sao</option>
            <option value="1">1 sao</option>
        </select>
        
        <label>Bình luận:</label>
        <textarea name="binhluan" required></textarea>
        <button type="submit">Gửi đánh giá</button>
        <button type="reset">Hủy đánh giá</button>
    </form>
</div>

    <!-- Nút xem thêm & viết đánh giá -->
    <div class="review-buttons">
        <button class="button view-more">Xem thêm đánh giá</button>
        <button class="button write-review">Viết đánh giá</button>
    </div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let offset = 5; // Bắt đầu từ đánh giá thứ 6
    const limit = 5; // Số lượng đánh giá mỗi lần
    const loadMoreBtn = document.querySelector(".view-more");
    const reviewList = document.querySelector(".review-list");

    loadMoreBtn.addEventListener("click", function () {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `load_more_reviews.php?masp=<?php echo $maSP; ?>&limit=${limit}&offset=${offset}`, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                if (xhr.responseText.trim() !== "") {
                    reviewList.innerHTML += xhr.responseText;
                    offset += limit; // Tăng offset để lần sau lấy tiếp
                } else {
                    loadMoreBtn.style.display = "none"; // Ẩn nút khi không còn đánh giá
                }
            }
        };
        xhr.send();
    });
});
</script>

<script>
document.querySelector(".write-review").addEventListener("click", function () {
    document.querySelector(".review-form").style.display = "block";
});

document.querySelector("#reviewForm").addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch("binhluan.php", {
        method: "POST",
        body: formData
    }).then(response => response.text()).then(data => {
        if (data === "success") {
            alert("Đánh giá của bạn đã được gửi!");
            location.reload(); // Tải lại trang để cập nhật đánh giá mới
        } else {
            alert("Có lỗi xảy ra, vui lòng thử lại.");
        }
    });
});
</script>

<script>
function openTab(event, tabName) {
    var i, tabcontent, tabbuttons;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tabbuttons = document.getElementsByClassName("tab-button");
    for (i = 0; i < tabbuttons.length; i++) {
        tabbuttons[i].classList.remove("active");
    }
    document.getElementById(tabName).style.display = "block";
    event.currentTarget.classList.add("active");
}
</script>
            

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