<?php
    require_once dirname(__DIR__, 2) . '/config/db.php';
    try {
        // Chỉ chọn các cột có tồn tại trong bảng
        $stmt = $pdo->query("SELECT id, name FROM categories"); 
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $categories = []; 
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group bán sách - GroupTwo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4t7gDlw8HrkKAsw6FkSf1s3Tkqw-4mBM8RUNEx49fTw&s=10">
    <style>
        .search-box { max-width: 600px; width: 100%; }
        .navbar { border-bottom: 1px solid #ebebeb; background-color: #fff !important; }
        .btn-category { color: #7a7e7f; transition: 0.3s; }
        .btn-category:hover { color: #0047ab; }
        .dropdown-menu { border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 36px; }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.08)!important; }


        /* Style cho Badge hiển thị số lượng ở góc trên bên phải của icon giỏ hàng */
        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #cd1818;
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            border-radius: 50%;
            padding: 2px 6px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        /* Hiệu ứng nảy nhẹ khi số lượng thay đổi */
        .cart-badge.bump {
            transform: scale(1.3);
        }

        /* Style cho viên tròn bay hiệu ứng */
        .flying-dot {
            position: fixed;
            width: 20px;
            height: 20px;
            background-color: #cd1818;
            border-radius: 50%;
            z-index: 9999;
            pointer-events: none; /* Không cản trở người dùng click cái khác */
            transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1); /* Đường cong di chuyển mượt mà */
        }
        /* CSS Định hình quả cầu bay đỏ */
        .flying-dot {
            position: fixed;
            width: 18px;
            height: 18px;
            background-color: #cd1818;
            border-radius: 50%;
            z-index: 99999;
            pointer-events: none;
            transition: left 0.8s cubic-bezier(0.25, 1, 0.5, 1), 
                        top 0.8s cubic-bezier(0.25, 1, 0.5, 1), 
                        transform 0.8s ease, 
                        opacity 0.8s ease;
        }

        /* Hiệu ứng nảy số lượng khi nhận hàng thành công */
        @keyframes bumpEffect {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(0, -5px) scale(1.3); }
            100% { transform: translate(0, 0) scale(1); }
        }
        .bump {
            animation: bumpEffect 0.25s ease-in-out;
        }
    </style>
</head>
<body >

    <div class="text-white text-center py-2 fw-bold small" style="background-color: #0047ab;">
        🔥 ĐÓN HÈ RỰC RỠ - SĂN SALE NGẬP TRÀN GIẢM GIÁ LÊN ĐẾN 50%! 🔥
    </div>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top bg-white shadow-sm py-2 py-md-3">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2 gap-md-4">
            
            <div class="d-flex align-items-center gap-2 gap-md-3"> 
                <a class="navbar-brand fw-bold mb-0" href="index.php" style="color: #0047ab; line-height: 1; font-size: 1.5rem; @media (min-width: 768px) { font-size: 1.75rem; }">
                    Group<span class="text-dark">Two.com</span>
                </a>

                <div class="dropdown d-none d-md-block">
                    <button class="btn btn-white border-0 btn-category d-flex align-items-center gap-1 p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-grid-alt fs-3 text-secondary'></i>
                        <i class='bx bx-chevron-down text-muted' style="font-size: 0.8rem;"></i>
                    </button>
                    
                    
                    <ul class="dropdown-menu mt-3 shadow border-0" aria-labelledby="dropdownMenuButton">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <li>
                                    <a class="dropdown-item py-2" href="index.php?category=<?= htmlspecialchars($cat['id']) ?>">
                                        <i class='bx bx-book me-2 text-primary'></i> 
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span class="dropdown-item py-2 text-muted">Chưa có danh mục</span></li>
                        <?php endif; ?>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="index.php">Xem tất cả</a></li>
                    </ul>
                </div>
            </div>

            <div class="navbar-nav d-flex flex-row align-items-center gap-3 gap-md-4 flex-shrink-0 order-md-3">
                <a class="nav-link text-secondary text-center px-1 py-0 position-relative" href="#" style="transition: color 0.2s;">
                    <i class='bx bx-bell fs-2 fs-md-3 d-block'></i>
                    <span class="small d-none d-xl-inline text-muted" style="font-size: 0.75rem;"></span>
                </a>
                <a href="index.php?page=cart" class="position-relative text-secondary me-3">
                    <i class='bx bx-shopping-bag fs-2' id="cart-icon"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                        <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?>
                    </span>
                </a>
                <!-- <a class="nav-link text-secondary text-center px-1 py-0" href="index.php?page=profile" style="transition: color 0.2s;">
                    <i class='bx bx-user fs-2 fs-md-3 d-block'></i>
                    <span class="small d-none d-xl-inline text-muted" style="font-size: 0.75rem;">Tài khoản</span>
                </a> -->
                <div class="dropdown">
                    <div class="dropdown d-flex align-items-center">
                        <?php if (isset($_SESSION['user'])): 
                            // Tránh lỗi nếu database không có trường fullname hoặc username
                            $nameDisplay = !empty($_SESSION['user']['fullname']) ? $_SESSION['user']['fullname'] : (!empty($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'Khách');
                            
                            // Lấy chữ cái đầu tiên (hỗ trợ cả tiếng Việt có dấu nhờ mb_substr)
                            $firstLetter = mb_strtoupper(mb_substr($nameDisplay, 0, 1, "UTF-8"), "UTF-8");
                        ?>
                            <div class="dropdown-toggle user-dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar-placeholder" style="width: 35px; height: 35px; background: linear-gradient(135deg, #0047ab, #00d2ff); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem; box-shadow: 0 2px 6px rgba(0,71,171,0.2);">
                                    <?= $firstLetter ?>
                                </div>
                                <span class="small d-none d-md-inline text-dark fw-medium text-truncate" style="max-width: 100px;">
                                    <?= htmlspecialchars($nameDisplay) ?>
                                </span>
                            </div>
                            
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="z-index: 9999;">
                                <li class="px-3 py-2 border-bottom d-md-none">
                                    <span class="fw-bold text-dark small d-block"><?= htmlspecialchars($nameDisplay) ?></span>
                                </li>
                                <li>
                                    <a class="dropdown-item p-2 small px-3 text-dark" href="index.php?page=profile">
                                        <i class='bx bx-id-card align-middle me-2 text-primary fs-5'></i> Thông tin cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item p-2 small px-3 text-dark" href="index.php?page=my-orders">
                                        <i class='bx bx-package align-middle me-2 text-warning fs-5'></i> Đơn hàng của tôi
                                    </a>
                                </li>
                                
                                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item p-2 small px-3 text-danger fw-bold" href="../admin/index.php">
                                            <i class='bx bx-shield-quarter align-middle me-2 fs-5'></i> Trang quản trị (Admin)
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item p-2 small px-3 text-secondary" href="auth/logout.php">
                                        <i class='bx bx-log-out align-middle me-2 text-danger fs-5'></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>

                        <?php else: ?>
                            <div class="dropdown-toggle user-dropdown-toggle d-flex flex-column align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bx-user fs-2 fs-md-3 text-secondary d-block'></i>
                                <span class="small d-none d-xl-inline text-muted" style="font-size: 0.75rem; margin-top: -2px;">Tài khoản</span>
                            </div>
                            
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="z-index: 9999;">
                                <li>
                                    <a class="dropdown-item p-2 small px-3 text-dark fw-bold" href="auth/login.php">
                                        <i class='bx bx-log-in align-middle me-2 text-success fs-5'></i> Đăng nhập
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item p-2 small px-3 text-dark" href="auth/register.php">
                                        <i class='bx bx-user-plus align-middle me-2 text-info fs-5'></i> Đăng ký tài khoản
                                    </a>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <form class="search-box w-100 flex-grow-1 mb-0 order-md-2 mt-2 mt-md-0" action="index.php" method="GET" style="max-width: 650px;">
                <div class="input-group">
                    <input class="form-control rounded-start-pill px-3 px-md-4 border-2" type="search" name="search" placeholder="Tìm kiếm sản phẩm mong muốn..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="border-color: #0047ab; height: 42px; font-size: 0.95rem; box-shadow: none;">
                    
                    <button class="btn text-white rounded-end-pill px-3 px-md-4 d-flex align-items-center justify-content-center" type="submit" style="background-color: #0047ab; height: 42px; min-width: 55px; border: 2px solid #0047ab;">
                        <i class='bx bx-search fs-4' style="line-height: 1;"></i>
                    </button>
                </div>
            </form>

        </div>
    </nav>

    <style>
        .nav-link:hover i, .nav-link:hover span {
            color: #0047ab !important;
        }
        .form-control:focus {
            border-color: #0047ab !important;
            background-color: #fffdfd;
        }
    </style>