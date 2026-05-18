<?php
include_once 'layouts/header.php';
// Nhúng file kết nối database của nhóm
include_once '../config/config.db'; 

// Từ khóa và danh mục
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$selected_category = isset($_GET['category']) ? trim($_GET['category']) : '';

// 1. QUERY LẤY DỮ LIỆU SÁCH CÓ BỘ LỌC VÀ TÌM KIẾM
$sql = "SELECT * FROM books WHERE 1=1";
$params = [];

if ($search_keyword !== '') {
    $sql .= " AND title LIKE :search";
    $params[':search'] = '%' . $search_keyword . '%';
}
if ($selected_category !== '') {
    $sql .= " AND category = :category";
    $params[':category'] = $selected_category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$filtered_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. QUERY CHO SẢN PHẨM FLASH SALE 
$stmt_flash = $pdo->prepare("SELECT * FROM books WHERE is_flashsale = 1 LIMIT 5");
$stmt_flash->execute();
$flash_books = $stmt_flash->fetchAll(PDO::FETCH_ASSOC);

// Boxicons
$quick_icons = [
    ['icon' => 'bx bxs-calendar', 'title' => '15.05', 'color' => '#ff4d4f'],
    ['icon' => 'bx bxs-coupon', 'title' => 'Ưu Đãi Siêu To', 'color' => '#722ed1'],
    ['icon' => 'bx bxs-bolt', 'title' => 'Flash Sale', 'color' => '#fa8c16'],
    ['icon' => 'bx bx-badge-check', 'title' => 'Mã Giảm Giá', 'color' => '#13c2c2'],
    ['icon' => 'bx bxs-truck', 'title' => 'Fahasa Deli', 'color' => '#eb2f96'],
    ['icon' => 'bx bx-book-open', 'title' => 'Alpha Books', 'color' => '#faad14'],
    ['icon' => 'bx bxs-star', 'title' => 'Sản Phẩm Mới', 'color' => '#52c41a'],
    ['icon' => 'bx bx-store', 'title' => 'Phiên Chợ Đồ Cũ', 'color' => '#1890ff'],
    ['icon' => 'bx bx-globe', 'title' => 'Ngoại Văn', 'color' => '#fa541c'],
    ['icon' => 'bx bxs-paint', 'title' => 'Manga', 'color' => '#eb2f96']
];
?>

<div class="container my-3 bg-gray">
    <?php if ($search_keyword === '' && $selected_category === ''): ?>
    <div class="row g-2 mb-4">
        <div class="col-lg-8 col-md-12">
            <div class="p-5 text-white rounded-3 shadow-sm d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #0072ff 0%, #00c6ff 100%); min-height: 280px;">
                <h1 class="fw-bold text-uppercase">Mở Khóa Ngôn Ngữ<br>Mở Khóa Thế Giới</h1>
                <p class="mb-0">Học thông qua trải nghiệm thực chiến cùng Nhóm 2</p>
            </div>
        </div>
        <div class="col-lg-4 d-none d-lg-flex flex-column gap-2">
            <div class="p-3 text-white rounded-3 flex-fill d-flex align-items-center" style="background: linear-gradient(135deg, #f53d3d 0%, #f77737 100%);">
                <div><h6 class="fw-bold mb-1">Ví Zalopay</h6><p class="small mb-0 opacity-75">Nhập mã giảm ngay 25K</p></div>
            </div>
            <div class="p-3 text-dark rounded-3 flex-fill d-flex align-items-center" style="background: linear-gradient(135deg, #fff2e6 0%, #ffe0b2 100%);">
                <div><h6 class="fw-bold text-danger mb-1">Nhập Mã Nhận Ưu Đãi</h6><p class="small mb-0 text-muted">Ưu đãi độc quyền</p></div>
            </div>
        </div>
    </div>

    <div class="bg-white p-3 rounded-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center text-center overflow-auto py-2" style="gap: 15px; white-space: nowrap;">
            <?php foreach ($quick_icons as $ico): ?>
                <div class="d-inline-block px-2" style="min-width: 85px;">
                    <div class="mx-auto d-flex align-items-center justify-content-center rounded-3 text-white fs-4 mb-2 shadow-2xs" style="width: 45px; height: 45px; background-color: <?php echo $ico['color']; ?>;">
                        <i class='<?php echo $ico['icon']; ?>'></i>
                    </div>
                    <span class="d-block text-dark fw-medium" style="font-size: 0.75rem; white-space: normal; line-height: 1.2;"><?php echo $ico['title']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card border-0 rounded-3 shadow-sm overflow-hidden mb-4">
        <div class="card-header border-0 text-white d-flex flex-wrap align-items-center justify-content-between py-3" style="background: linear-gradient(90deg, #ff4d4f 0%, #ff7875 100%);">
            <div class="d-flex align-items-center gap-2">
                <h3 class="fw-bold text-uppercase mb-0 fs-4 text-warning" style="font-style: italic;">⚡ Flash Sale</h3>
                <div class="d-flex align-items-center gap-1 ms-3" id="countdown-box">
                    <span class="badge bg-dark px-2 py-1" id="timer-hour">00</span> :
                    <span class="badge bg-dark px-2 py-1" id="timer-min">00</span> :
                    <span class="badge bg-dark px-2 py-1" id="timer-sec">00</span>
                </div>
            </div>
        </div>
        <div class="card-body bg-white p-3">
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-3">
                <?php foreach ($flash_books as $book): ?>
                    <div class="col">
                        <div class="card h-100 border-light position-relative card-hover shadow-2xs p-2">
                            <span class="position-absolute top-0 start-0 badge bg-danger m-2">-<?php echo $book['sale']; ?></span>
                            <div class="text-center py-2"><img src="../public/images/<?php echo $book['image']; ?>" class="img-fluid rounded" style="max-height: 140px; object-fit: contain;" onerror="this.src='https://placehold.co/150x200?text=GroupTwo'"></div>
                            <div class="card-body p-1 d-flex flex-column justify-content-between">
                                <h6 class="card-title text-dark small mb-1 text-truncate-2"><?php echo $book['title']; ?></h6>
                                <div>
                                    <div class="mb-2"><span class="text-danger fw-bold"><?php echo number_format($book['price']); ?> đ</span></div>
                                    <div class="progress rounded-pill style-progress" style="height: 14px; position: relative;">
                                        <div class="progress-bar bg-danger w-75" role="progressbar"></div>
                                        <small class="w-100 text-center text-white position-absolute" style="font-size: 0.65rem; line-height: 14px; left: 0;">Sắp cháy hàng</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white p-4 rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h4 class="fw-bold mb-0 text-uppercase fs-5 text-danger">
                <?php echo ($search_keyword !== '') ? '🔍 Kết quả tìm kiếm: "' . htmlspecialchars($search_keyword) . '"' : '📈 Xu Hướng Mua Sắm'; ?>
            </h4>
            <span class="badge bg-secondary rounded-pill">Tìm thấy: <?php echo count($filtered_books); ?></span>
        </div>

        <?php if (count($filtered_books) === 0): ?>
            <div class="text-center py-5">
                <p class="text-muted">Không tìm thấy sản phẩm nào khớp với từ khóa. 😢</p>
                <a href="index.php" class="btn text-white px-4" style="background-color: #cd1818;">Xem tất cả</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                <?php foreach ($filtered_books as $book): ?>
                    <div class="col">
                        <div class="card h-100 card-hover border-light position-relative shadow-2xs rounded-3 p-2">
                            <span class="position-absolute top-0 start-0 badge bg-danger m-2">-<?php echo $book['sale']; ?></span>
                            <div class="text-center py-3">
                                <img src="../public/images/<?php echo $book['image']; ?>" class="img-fluid rounded" style="max-height: 160px; object-fit: contain;" onerror="this.src='https://placehold.co/150x200?text=GroupTwo'">
                            </div>
                            <div class="card-body p-1 d-flex flex-column justify-content-between">
                                <h6 class="card-title text-dark fw-semibold small mb-2 text-truncate-2"><?php echo $book['title']; ?></h6>
                                <div>
                                    <p class="card-text mb-2">
                                        <span class="text-danger fw-bold d-block"><?php echo number_format($book['price']); ?> đ</span>
                                        <span class="text-muted text-decoration-line-through x-small" style="font-size: 0.75rem;"><?php echo number_format($book['old_price']); ?> đ</span>
                                    </p>
                                    <a href="detail.php?id=<?php echo $book['id']; ?>" class="btn w-100 text-white rounded-2 btn-sm fw-bold" style="background-color: #cd1818;">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function startCountdown(duration) {
        let timer = duration;
        setInterval(function () {
            let hours = parseInt(timer / 3600, 10), minutes = parseInt((timer % 3600) / 60, 10), seconds = parseInt(timer % 60, 10);
            document.getElementById('timer-hour').textContent = hours < 10 ? "0" + hours : hours;
            document.getElementById('timer-min').textContent = minutes < 10 ? "0" + minutes : minutes;
            document.getElementById('timer-sec').textContent = seconds < 10 ? "0" + seconds : seconds;
            if (--timer < 0) timer = duration;
        }, 1000);
    }
    window.onload = function () { startCountdown(28800); };
</script>

<?php include_once 'layouts/footer.php'; ?>