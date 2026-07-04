
    <?php
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    session_start();
    include_once 'layouts/header.php';
    include_once '../config/db.php'; 
    
    $page = $_GET['page'] ?? 'home';
    
    switch ($page) {
        case 'home':
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
                $sql .= " AND category_id = :category"; 
                $params[':category'] = $selected_category;
            }
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $filtered_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // 2. QUERY CHO SẢN PHẨM FLASH SALE 
            $current_time = date('Y-m-d H:i:s');
            $stmt_flash = $pdo->prepare("
                SELECT * FROM books 
                WHERE sale > 0 
                AND sale_start <= :now 
                AND sale_end >= :now
            ");
            $stmt_flash->execute([':now' => date('Y-m-d H:i:s')]);
            $flash_books = $stmt_flash->fetchAll(PDO::FETCH_ASSOC);

            // Debug: Nếu vẫn không ra, thêm dòng này ngay dưới để check
            if (empty($flash_books)) {
                echo "<!-- Debug: Không tìm thấy sản phẩm sale. Now: " . date('Y-m-d H:i:s') . " -->";
            }
    
            // Boxicons danh mục nhanh
            $quick_icons = [
                ['icon' => 'bx bxs-bolt', 'title' => 'Flash Sale', 'color' => '#fa8c16', 'link' => 'index.php?page=comming_soon'],
                ['icon' => 'bx bx-badge-check', 'title' => 'Mã Giảm Giá', 'color' => '#13c2c2', 'link' => 'index.php?page=comming_soon'],
                ['icon' => 'bx bxs-star', 'title' => 'New', 'color' => '#52c41a', 'link' => 'index.php?page=comming_soon'],
                ['icon' => 'bx bx-bolt-circle', 'title' => 'Đồ Công Nghệ', 'color' => '#1073ec', 'link' => 'index.php?page=comming_soon'],
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
                    <div class="d-flex justify-content-center align-items-center text-center overflow-auto py-2" style="gap: 15px;">
                    <?php foreach ($quick_icons as $ico): ?>
                        <a href="<?php echo $ico['link']; ?>" class="text-decoration-none" style="min-width: 85px;">
                            <div class="d-inline-block px-2">
                                <div class="mx-auto d-flex align-items-center justify-content-center rounded-3 text-white fs-4 mb-2 shadow-2xs" style="width: 45px; height: 45px; background-color: <?php echo $ico['color']; ?>;">
                                    <i class='<?php echo $ico['icon']; ?>'></i>
                                </div>
                                <span class="d-block text-dark fw-medium" style="font-size: 0.75rem;"><?php echo $ico['title']; ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                </div>
    
                <div class="card border-0 rounded-3 shadow-sm overflow-hidden mb-4">
                    <div class="card-header border-0 text-white d-flex flex-wrap align-items-center justify-content-between py-3" style="background: linear-gradient(90deg, #0047ab 0%, #ff7875 100%);">
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold text-uppercase mb-0 fs-4 text-warning" style="font-style: italic;">⚡ Flash Sale</h3>
                            <!-- <div class="d-flex align-items-center gap-1 ms-3" id="countdown-box">
                                <span class="badge bg-dark px-2 py-1" id="timer-hour">00</span> :
                                <span class="badge bg-dark px-2 py-1" id="timer-min">00</span> :
                                <span class="badge bg-dark px-2 py-1" id="timer-sec">00</span>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-body bg-white p-3">
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-3">
                            <?php foreach ($flash_books as $book): ?>
                                <div class="col">
                                    <a href="index.php?page=detail&id=<?php echo $book['id']; ?>" class="text-decoration-none text-dark d-block h-100">
                                        <div class="card h-100 border-light position-relative card-hover shadow-2xs p-2">
                                            <span class="position-absolute top-0 start-0 badge bg-danger m-2">-<?php echo $book['sale'] ?? '0%'; ?></span>
                                            
                                            <div class="text-center py-2">
                                                <?php
                                                if (strpos($book['image'], 'http') === 0) {
                                                    $img_src = $book['image'];
                                                } else {
                                                    $img_src = '../public/images/' . $book['image'];
                                                }
                                                ?>

                                                <img src="<?php echo $img_src; ?>" 
                                                    class="img-fluid rounded" 
                                                    style="max-height: 160px; object-fit: contain;" 
                                                    onerror="this.src='https://placehold.co/150x200?text=Error'">
                                            </div>
                                            
                                            <div class="card-body p-1 d-flex flex-column justify-content-between">
                                                <h6 class="card-title text-dark fw-semibold small mb-2 text-truncate-2" style="line-height: 1.4;"><?php echo $book['title']; ?></h6>
                                                <div>
                                                    <p class="card-text mb-2">
                                                        <span class="text-danger fw-bold d-block"><?php echo number_format($book['price']); ?> đ</span>
                                                        <span class="text-muted text-decoration-line-through x-small" style="font-size: 0.75rem;"><?php echo number_format($book['old_price'] ?? 0); ?> đ</span>
                                                    </p>
                                                    <div class="btn w-100 text-white rounded-2 btn-sm fw-bold" style="background-color: #cd1818;">Xem chi tiết</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
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
                    <?php die; else: ?>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                            <?php foreach ($filtered_books as $book): ?>
                                <div class="col">
                                    <a href="index.php?page=detail&id=<?php echo $book['id']; ?>" class="text-decoration-none text-dark d-block h-100">
                                        <div class="card h-100 card-hover border-light position-relative shadow-2xs rounded-3 p-2">
                                            <span class="position-absolute top-0 start-0 badge bg-danger m-2"><?php echo $book['sale'] ?? '0%'; ?></span>
                                            
                                            <div class="text-center py-3">
                                                <?php
                                                    if (strpos($book['image'], 'http') === 0) {
                                                        $img_src = $book['image'];
                                                    } else {
                                                        $img_src = '../public/images/' . $book['image'];
                                                    }
                                                    ?>

                                                    <img src="<?php echo $img_src; ?>" 
                                                        class="img-fluid rounded" 
                                                        style="max-height: 160px; object-fit: contain;" 
                                                        onerror="this.src='https://placehold.co/150x200?text=Error'">
                                            </div>
                                            
                                            <div class="card-body p-1 d-flex flex-column justify-content-between">
                                                <h6 class="card-title text-dark fw-semibold small mb-2 text-truncate-2" style="line-height: 1.4;"><?php echo $book['title']; ?></h6>
                                                <div>
                                                    <p class="card-text mb-2">
                                                        <span class="text-danger fw-bold d-block"><?php echo number_format($book['price']); ?> đ</span>
                                                        <span class="text-muted text-decoration-line-through x-small" style="font-size: 0.75rem;"><?php echo number_format($book['old_price'] ?? 0); ?> đ</span>
                                                    </p>
                                                    <div class="btn w-100 text-white rounded-2 btn-sm fw-bold" style="background-color: #cd1818;">Xem chi tiết</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- <script>
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
            </script> -->
            <script>
                document.querySelectorAll('[data-end]').forEach(function(el) {
                    let endTime = new Date(el.getAttribute('data-end')).getTime();
                    
                    let interval = setInterval(function() {
                        let now = new Date().getTime();
                        let distance = endTime - now;
    
                        if (distance < 0) {
                            clearInterval(interval);
                            el.closest('.col').style.display = 'none';
                            return;
                        }
                    }, 1000);
                });
                </script>
            <?php
            break;
    
        case 'detail':
            include 'view/detail.php';
            break;
    
        case 'profile':
            // if (!isset($_SESSION['user'])){
            //     echo "<script>window.location.href = 'auth/login.php';</script>";
            //     exit();
            // }
            include 'view/profile.php';
            break;
    
        case 'cart':
            include 'view/cart.php';
            break;
    
        case 'flash_sale': 
            include 'view/flash_sale.php';
            break;
    
        case 'new_products': 
            include 'view/new_products.php';
            break;

        case 'comming_soon':
            include 'view/comming_soon.php';
            break;
    
    
        case 'checkout':
            include 'view/checkout.php';
            break;
    
        default:
            echo "<h3 class='text-center my-5'>Trang không tồn tại!</h3>";
            break;
    }
    ?>
    
    <?php
    include_once 'layouts/footer.php'; 
    ?>
    
    <script>
        document.querySelectorAll('.dev-link').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault(); // Ngăn chặn hành động mặc định của thẻ a
                const title = this.getAttribute('data-title');
                alert('Tính năng "' + title + '" hiện đang được phát triển. Bạn hãy kiên nhẫn chờ nhé!');
            });
        });
    </script>
