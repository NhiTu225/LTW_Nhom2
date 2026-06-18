<?php
// 1. Khởi động Session và cấu hình bật lỗi để dễ kiểm soát
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔒 CHỐT CHẶN BẢO MẬT (Bạn có thể bỏ comment nếu hệ thống đã có tính năng login)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../client/index.php");
    exit();
}

// 2. Kết nối Database (Tìm file db.php nằm trong thư mục config)
if (file_exists('../config/db.php')) {
    include_once '../config/db.php';
}

// 3. Đọc tham số điều hướng (?page=...). Mặc định không truyền gì là 'dashboard'
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Lấy thông tin tài khoản hiển thị lên góc phải
$admin_fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin User';
$admin_email = isset($_SESSION['email']) ? $_SESSION['email'] : 'admin@bookstore.com';
$avatar_letter = mb_substr($admin_fullname, 0, 1, 'utf-8');
?>

<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hệ Thống Quản Trị - BookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
      :root { --sb-bg: #1e2640; --sb-hover: #2d3758; --body-bg: #f4f6f9; }
      body { background-color: var(--body-bg); font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; overflow-x: hidden; }
      
      /* Sidebar Layout cố định */
      .sidebar { height: 100vh; background: var(--sb-bg); color: #fff; width: 260px; position: fixed; top: 0; left: 0; z-index: 1000; padding: 1.5rem; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
      .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 0.85rem 1.2rem; border-radius: 0.5rem; margin: 0.2rem 0; font-weight: 500; text-decoration: none; display: block; transition: all 0.2s ease; }
      .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #0d6efd; color: #fff; }
      
      /* Vùng chứa nội dung chính bên phải Sidebar */
      .main-content { margin-left: 260px; min-height: 100vh; padding: 1.5rem 2rem; }
      .panel-box { border: none; border-radius: 1rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02); background: #fff; padding: 1.5rem; }
    </style>
  </head>
  <body>

    <div class="d-flex">
      <aside class="sidebar d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex align-items-center gap-3 mb-4 pb-2 px-1">
            <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width:38px; height:38px;">
              <i class="fa-solid fa-book-open fs-5"></i>
            </div>
            <span class="fs-5 fw-bold text-white">BookStore Admin</span>
          </div>
          <hr class="text-secondary opacity-25">
          <nav class="nav flex-column mt-3">
            <a class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard"><i class="fa-solid fa-chart-pie me-3"></i>Dashboard</a>
            <a class="nav-link <?= $page == 'book-list' || $page == 'book-add' || $page == 'book-edit' ? 'active' : '' ?>" href="index.php?page=book-list"><i class="fa-solid fa-book me-3"></i>Quản Lý Sách</a>
            <a class="nav-link <?= $page == 'category-list' ? 'active' : '' ?>" href="index.php?page=category-list"><i class="fa-solid fa-layer-group me-3"></i>Danh Mục</a>
            <a class="nav-link <?= $page == 'user-list' ? 'active' : '' ?>" href="index.php?page=user-list"><i class="fa-solid fa-users me-3"></i>Quản Lý KH</a>
            <a class="nav-link <?= $page == 'order-list' ? 'active' : '' ?>" href="index.php?page=order-list"><i class="fa-solid fa-file-invoice-dollar me-3"></i>Quản Lý Đơn Hàng</a>
          </nav>
        </div>

        <div class="mt-auto mb-3">

            <hr class="text-secondary opacity-25 my-3">
            <a href="modules/logout.php" 
               class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3" 
               style="text-decoration: none; color: #ff4d4d !important; font-weight: 700 !important; font-size: 0.95rem; transition: all 0.2s ease-in-out;"
               onmouseover="this.style.backgroundColor='rgba(243, 24, 24, 0.3)'; this.style.color='#fff !important'"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ff4d4d !important'"
               onclick="return confirm('Bạn có chắc chắn muốn đăng xuất khỏi hệ thống không?')">
                <i class="fa-solid fa-right-from-bracket fs-5"></i> <span>Đăng xuất</span>
            </a>
        </div>

        <div class="text-secondary small px-1" style="font-size: 0.75rem; opacity: 0.6;">Phiên bản v1.0</div>
      </aside>

      <div class="main-content flex-grow-1"> 

        <header class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm mb-4">
          <h5 class="fw-bold text-dark m-0 d-none d-lg-block">
             <i class="fa-solid fa-desktop text-primary me-2 small"></i>Hệ Thống Quản Lý Cửa Hàng
          </h5>
          <div class="d-flex align-items-center gap-3 ms-auto">
            <div class="text-end">
              <p class="m-0 fw-semibold text-dark small"><?= htmlspecialchars($admin_fullname) ?></p>
              <small class="text-muted d-block" style="font-size: 0.72rem;"><?= htmlspecialchars($admin_email) ?></small>
            </div>
            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;"><?= htmlspecialchars($avatar_letter) ?></div>
          </div>
        </header>

        <?php
        switch ($page) {
            case 'dashboard':
                if (file_exists('view/dashboard.php')) {
                    include_once 'view/dashboard.php';
                } else {
                    echo "<div class='alert alert-warning'>Vui lòng tạo file view/dashboard.php để chứa giao diện Thống kê.</div>";
                }
                break;
                
            case 'book-list':
                if (file_exists('view/book-list.php')) {
                    include_once 'view/book-list.php';
                } else {
                    echo "<div class='alert alert-warning'>Vui lòng tạo file view/book-list.php để chứa giao diện Bảng danh sách sách.</div>";
                }
                break;
                
            case 'book-add':
                if (file_exists('view/book-add.php')) {
                    include_once 'view/book-add.php';
                } else {
                    echo "<div class='alert alert-warning'>Vui lòng tạo file view/book-add.php để chứa giao diện Form thêm sách.</div>";
                }
                break;
                
            case 'book-edit':
                if (file_exists('view/book-edit.php')) {
                    include_once 'view/book-edit.php';
                } else {
                    echo "<div class='alert alert-warning'>Vui lòng tạo file view/book-edit.php để chứa giao diện Form sửa sách.</div>";
                }
                break;

            case 'category-list':
                if (file_exists('view/category-list.php')) {
                    include_once 'view/category-list.php';
                } else {
                    echo "<div class='alert alert-warning'>Vui lòng tạo file view/category-list.php để chứa giao diện Quản lý danh mục.</div>";
                }
                break;

            case 'user-list':
                if (file_exists('view/user-list.php')) {
                    include_once 'view/user-list.php';
                } else {
                    echo "<div class='alert alert-warning'>Vui lòng tạo file view/user-list.php để chứa giao diện Quản lý khách hàng.</div>";
                }
                break;

            case 'order-list':
                if (file_exists('view/order-list.php')) {
                    include_once 'view/order-list.php';
                } else {
                    echo "<div class='alert alert-warning'>Vui lòng tạo file view/order-list.php để chứa giao diện Quản lý đơn hàng.</div>";
                }
                break;

            default:
                echo "<div class='alert alert-danger'>Trang không tồn tại hoặc tính năng đang phát triển!</div>";
                break;
        }
        ?>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>