
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fahasa Khởi Sắc - Tiệm Sách Nhóm 2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .search-box { max-width: 600px; width: 100%; }
        .navbar { border-bottom: 1px solid #ebebeb; background-color: #fff !important; }
        .btn-category { color: #7a7e7f; transition: 0.3s; }
        .btn-category:hover { color: #cd1818; }
        .dropdown-menu { border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 36px; }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.08)!important; }
    </style>
</head>
<body>

    <div class="text-white text-center py-2 fw-bold small" style="background-color: #cd1818;">
        🔥 ĐÓN HÈ RỰC RỠ - SĂN SALE NGẬP TRÀN GIẢM GIÁ LÊN ĐẾN 50%! 🔥
    </div>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-2">
        <div class="container d-flex align-items-center justify-content-between">
            
            <div class="d-flex align-items-center gap-4"> 
                <a class="navbar-brand fw-bold fs-3 mb-0" href="index.php" style="color: #cd1818; line-height: 1;">
                    Group<span class="text-dark fs-4">Two.com</span>
                </a>

                <div class="dropdown d-none d-lg-block">
                    <button class="btn btn-white border-0 btn-category d-flex align-items-center gap-1 p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-grid-alt fs-3'></i>
                        <i class='bx bx-chevron-down' style="font-size: 0.8rem;"></i>
                    </button>
                    <ul class="dropdown-menu mt-3" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item py-2" href="index.php?category=it"><i class='bx bx-code-alt me-2'></i> Sách Công Nghệ</a></li>
                        <li><a class="dropdown-item py-2" href="index.php?category=economy"><i class='bx bx-trending-up me-2'></i> Sách Kinh Tế</a></li>
                        <li><a class="dropdown-item py-2" href="index.php?category=skills"><i class='bx bx-bulb me-2'></i> Tâm Lý Kỹ Năng</a></li>
                        <li><a class="dropdown-item py-2" href="index.php?category=language"><i class='bx bx-world me-2'></i> Góc Ngoại Ngữ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="index.php">Xem tất cả</a></li>
                    </ul>
                </div>
            </div>
            
            <form class="d-flex search-box mx-4 flex-grow-1" action="index.php" method="GET" style="max-width: 550px;">
                <div class="input-group">
                    <input class="form-control rounded-start-pill px-4 border-2" type="search" name="search" placeholder="Tìm kiếm sản phẩm mong muốn..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="border-color: #cd1818; height: 40px;">
                    
                    <button class="btn text-white rounded-end-pill px-4 d-flex align-items-center justify-content-center" type="submit" style="background-color: #cd1818; height: 40px; min-width: 60px;">
                        <i class='bx bx-search fs-4' style="line-height: 1;"></i>
                    </button>
                </div>
            </form>

            <div class="navbar-nav align-items-center flex-row gap-3">
                <a class="nav-link text-secondary position-relative text-center px-1 py-0" href="#">
                    <i class='bx bx-bell fs-4 d-block'></i>
                    <span class="small d-none d-md-inline" style="font-size: 0.75rem;">Thông Báo</span>
                </a>
                <a class="nav-link text-secondary position-relative text-center px-1 py-0" href="cart.php">
                    <i class='bx bx-shopping-bag fs-4 d-block'></i>
                    <span class="small d-none d-md-inline" style="font-size: 0.75rem;">Giỏ hàng</span>
                </a>
                <a class="nav-link text-secondary text-center px-1 py-0" href="login.php">
                    <i class='bx bx-user fs-4 d-block'></i>
                    <span class="small d-none d-md-inline" style="font-size: 0.75rem;">Tài khoản</span>
                </a>
            </div>

        </div>
    </nav>