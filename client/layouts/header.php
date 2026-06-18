
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group bán sách - GroupTwo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="icon" type="image/png" href="../../public/images/2.png">
    <style>
        .search-box { max-width: 600px; width: 100%; }
        .navbar { border-bottom: 1px solid #ebebeb; background-color: #fff !important; }
        .btn-category { color: #7a7e7f; transition: 0.3s; }
        .btn-category:hover { color: #0047ab; }
        .dropdown-menu { border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 36px; }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.08)!important; }
    </style>
</head>
<body>

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
                        <li><a class="dropdown-item py-2" href="index.php?category=it"><i class='bx bx-code-alt me-2 text-primary'></i> Sách Công Nghệ</a></li>
                        <li><a class="dropdown-item py-2" href="index.php?category=economy"><i class='bx bx-trending-up me-2 text-success'></i> Sách Kinh Tế</a></li>
                        <li><a class="dropdown-item py-2" href="index.php?category=skills"><i class='bx bx-bulb me-2 text-warning'></i> Tâm Lý Kỹ Năng</a></li>
                        <li><a class="dropdown-item py-2" href="index.php?category=language"><i class='bx bx-world me-2 text-info'></i> Góc Ngoại Ngữ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="index.php">Xem tất cả</a></li>
                    </ul>
                </div>
            </div>

            <div class="navbar-nav d-flex flex-row align-items-center gap-3 gap-md-4 flex-shrink-0 order-md-3">
                <a class="nav-link text-secondary text-center px-1 py-0 position-relative" href="#" style="transition: color 0.2s;">
                    <i class='bx bx-bell fs-2 fs-md-3 d-block'></i>
                    <span class="small d-none d-xl-inline text-muted" style="font-size: 0.75rem;">Thông Báo</span>
                </a>
                <a class="nav-link text-secondary text-center px-1 py-0 position-relative" href="cart.php" style="transition: color 0.2s;">
                    <i class='bx bx-shopping-bag fs-2 fs-md-3 d-block'></i>
                    <span class="small d-none d-xl-inline text-muted" style="font-size: 0.75rem;">Giỏ hàng</span>
                </a>
                <a class="nav-link text-secondary text-center px-1 py-0" href="../auth/login.php" style="transition: color 0.2s;">
                    <i class='bx bx-user fs-2 fs-md-3 d-block'></i>
                    <span class="small d-none d-xl-inline text-muted" style="font-size: 0.75rem;">Tài khoản</span>
                </a>
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