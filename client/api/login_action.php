<?php
session_start();

if (file_exists('../../config/db.php')) {
    require_once '../../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
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
                // Đăng nhập thành công -> Lưu nguyên mảng $user vào $_SESSION['user'] cho đồng bộ với Header
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'fullname' => $user['fullname'],
                    'email'    => $user['email'],
                    'role'     => $user['role'] // 'admin' hoặc 'user'
                ];
                $_SESSION['role'] = $user['role'];

                // Kiểm tra vai trò để điều hướng (Routing)
                if ($_SESSION['user']['role'] === 'admin') {
                    header("Location: ../../admin/index.php");
                } else {
                    header("Location: ../index.php");
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