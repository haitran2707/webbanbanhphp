<?php
session_start();

// Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
if (!isset($_SESSION['MaND']) || $_SESSION['QuyenND'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Thư viện Chart.js -->
    <style>
        /* Reset mặc định */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Bố cục trang */
        body {
            background-color: #f4f7f6;
            color: #333;
        }

        header {
            background: #2C3E50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        nav {
            background: #34495E;
            padding: 10px 0;
        }

        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
            transition: 0.3s;
        }

        nav ul li a:hover {
            background: #1ABC9C;
            border-radius: 5px;
        }

        /* Nội dung chính */
        .container {
            width: 90%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 15px;
        }

        ul {
            list-style: none;
            padding: 10px;
        }

        ul li {
            padding: 10px;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
        }

        ul li:last-child {
            border-bottom: none;
        }

        /* Thống kê nhanh */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .stat-box {
            background: #1ABC9C;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Biểu đồ */
        .chart-container {
            width: 80%;
            margin: 30px auto;
        }

        /* Button Xuất Excel */
        .export-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            text-align: center;
        }

        .export-button:hover {
            background-color: #218838;
        }

        /* Footer */
        footer {
            text-align: center;
            background: #2C3E50;
            color: white;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>Chào mừng Admin đến với trang quản trị</header>
    
    <nav>
        <ul>
            <li><a href="manage_products.php">Quản lý Sản Phẩm</a></li>
            <li><a href="manage_categories.php">Quản lý Danh Mục</a></li>
            <li><a href="manage_orders.php">Quản lý Đơn Hàng</a></li>
            <li><a href="manage_users.php">Quản lý Người Dùng</a></li>
            <li><a href="manage_reviews.php">Quản lý Đánh Giá</a></li>
            <li><a href="manage_news.php">Quản lý Tin Tức</a></li>
            <li><a href="manage_market.php">Quản lý Giỏ Hàng</a></li>
            <li><a href="manage_contacts.php">Quản lý Liên Hệ</a></li>
            <li><a href="login.php">Đăng xuất</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Tổng Quan</h2>
        <p>Dưới đây là các thống kê :</p>

        <?php
        include_once("connect.php");

        $productCount = $connect->query("SELECT COUNT(*) AS total FROM sanpham")->fetch_assoc()['total'];
        $orderCount = $connect->query("SELECT COUNT(*) AS total FROM donhang")->fetch_assoc()['total'];
        $userCount = $connect->query("SELECT COUNT(*) AS total FROM nguoidung")->fetch_assoc()['total'];
        $reviewCount = $connect->query("SELECT COUNT(*) AS total FROM danhgia")->fetch_assoc()['total'];
        ?>

        <div class="stats">
            <div class="stat-box">Sản phẩm: <?php echo $productCount; ?></div>
            <div class="stat-box">Đơn hàng: <?php echo $orderCount; ?></div>
            <div class="stat-box">Người dùng: <?php echo $userCount; ?></div>
            <div class="stat-box">Đánh giá: <?php echo $reviewCount; ?></div>
        </div>

        <?php
        $totalRevenue = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành'")->fetch_assoc()['total'];
        $todayRevenue = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành' AND DATE(NgayTao) = CURDATE()")->fetch_assoc()['total'];
        $monthRevenue = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành' AND MONTH(NgayTao) = MONTH(CURDATE())")->fetch_assoc()['total'];
        $yearRevenue = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành' AND YEAR(NgayTao) = YEAR(CURDATE())")->fetch_assoc()['total'];
        $quarterRevenue = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành' AND QUARTER(NgayTao) = QUARTER(CURDATE()) AND YEAR(NgayTao) = YEAR(CURDATE())")->fetch_assoc()['total'];
        ?>

        <h2>Báo Cáo Doanh Thu</h2>
        <ul>
            <li>Tổng doanh thu: <?php echo number_format($totalRevenue ?? 0, 0, ',', '.'); ?> VNĐ</li>
            <li>Doanh thu hôm nay: <?php echo number_format($todayRevenue ?? 0, 0, ',', '.'); ?> VNĐ</li>
            <li>Doanh thu tháng này: <?php echo number_format($monthRevenue ?? 0, 0, ',', '.'); ?> VNĐ</li>
            <li>Doanh thu năm nay: <?php echo number_format($yearRevenue ?? 0, 0, ',', '.'); ?> VNĐ</li>
            <li>Doanh thu quý này: <?php echo number_format($quarterRevenue ?? 0, 0, ',', '.'); ?> VNĐ</li>
        </ul>

        <div class="chart-container">
            <h2>Biểu Đồ Doanh Thu Theo Từng Tháng Trong Năm</h2>
            <canvas id="monthChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>Biểu Đồ Doanh Thu Theo Quý Trong Năm</h2>
            <canvas id="quarterlyChart"></canvas>
        </div>

        <?php
        // Doanh thu theo tháng
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $query = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành' AND MONTH(NgayTao) = $i");
            $row = $query->fetch_assoc();
            $monthlyData[] = $row['total'] ?: 0;
        }

        // Doanh thu theo quý
        $quarterlyData = [];
        for ($i = 1; $i <= 4; $i++) {
            $query = $connect->query("SELECT SUM(TongTien) AS total FROM donhang WHERE TrangThai = 'Hoàn thành' AND QUARTER(NgayTao) = $i");
            $row = $query->fetch_assoc();
            $quarterlyData[] = $row['total'] ?: 0;
        }
        ?>

    <script>
    var ctxMonth = document.getElementById('monthChart').getContext('2d');
    var revenueChart = new Chart(ctxMonth, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?php echo json_encode($monthlyData); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        }
    });

    var ctxQuarter = document.getElementById('quarterlyChart').getContext('2d');
    var quarterlyChart = new Chart(ctxQuarter, {
        type: 'line', 
        data: {
            labels: ['Quý 1', 'Quý 2', 'Quý 3', 'Quý 4'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?php echo json_encode($quarterlyData); ?>,
                backgroundColor: 'rgba(255, 159, 64, 0.5)', // màu nền cho vùng
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1,
                fill: true // thuộc tính này để làm đầy vùng dưới đường
            }]
        }
    });
    </script>
        <button class="export-button" onclick="window.location.href='xuat_bieu_do.php'">Xuất Excel</button>
    </div>

    <footer>© 2025 - Quản trị hệ thống Tam Thái Tử Bakery</footer>
</body>
</html>