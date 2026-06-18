<?php
session_start();
// Nhúng file kết nối database từ thư mục config
if (file_exists('../../config/db.php')) {
    require_once '../../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? ''); // Giả lập chuỗi thô theo DB mẫu, nếu dùng mã hóa hash thì thay bằng password_verify

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
        header("Location: login.php");
        exit();
    }

    if (isset($pdo)) {
        try {
            // Kiểm tra tài khoản trong Database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->execute([$username, $password]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Đăng nhập thành công -> Lưu thông tin vào Session
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email']    = $user['email'];
                $_SESSION['role']     = $user['role']; // Giá trị 'admin' hoặc 'user'

                // Kiểm tra vai trò để điều hướng (Routing) nhàn hạ
                if ($_SESSION['role'] === 'admin') {
                    header("Location: ../../admin/index.php"); // Nhảy vào trang quản trị
                } else {
                    header("Location: ../index.php"); // Nhảy vào trang chủ bán hàng
                }
                exit();
            } else {
                $_SESSION['error'] = "Tài khoản hoặc mật khẩu không chính xác!";
                header("Location: login.php");
                exit();
            }
        } catch (Exception $e) {
            die("Lỗi hệ thống đăng nhập: " . $e->getMessage());
        }
    }
} else {
    header("Location: login.php");
    exit();
}