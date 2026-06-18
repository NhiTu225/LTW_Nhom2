<?php
session_start();

// Chốt chặn bảo mật: Chỉ cho phép tài khoản quyền admin thực hiện
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../client/index.php");
    exit();
}

// Kết nối database
if (file_exists('../../../config/db.php')) {
    require_once '../../../config/db.php';
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0 && isset($pdo)) {
    try {
        // Thực thi lệnh xóa tài khoản người dùng (role = 'user' để an toàn, tránh xóa nhầm admin)
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
        $stmt->execute([$id]);

        // Xóa xong, quay trở lại trang danh sách khách hàng
        header("Location: ../../index.php?page=user-list");
        exit();
    } catch (Exception $e) {
        die("Lỗi khi xóa tài khoản khách hàng: " . $e->getMessage());
    }
} else {
    header("Location: ../../index.php?page=user-list");
    exit();
}