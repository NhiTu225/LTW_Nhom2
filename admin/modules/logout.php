<?php
session_start();

// Xóa sạch toàn bộ các biến trong Session
$_SESSION = array();

// Nếu sử dụng cookie session thì xóa luôn cookie đó (đảm bảo an toàn 100%)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hủy hoàn toàn session trên hệ thống
session_destroy();

// Điều hướng quay trở về trang đăng nhập của Client
header("Location: ../../client/auth/login.php");
exit();