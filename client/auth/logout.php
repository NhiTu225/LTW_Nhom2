<?php
session_start();

// Xóa bỏ thông tin user trong session
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

// Nếu muốn xóa sạch luôn cả giỏ hàng khi logout thì em dùng lệnh dưới (tùy chọn)
session_destroy();

// Đăng xuất xong, đá người dùng quay trở lại trang chủ client
header("Location: ../index.php");
exit();