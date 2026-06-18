<?php
session_start();
// Chốt chặn bảo mật nếu cần
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../client/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists('../../../config/db.php')) {
        require_once '../../../config/db.php';
    }

    $order_id = intval($_POST['order_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if ($order_id > 0 && !empty($status) && isset($pdo)) {
        try {
            // Cập nhật trạng thái mới cho đơn hàng
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $order_id]);
            
            // Thành công thì quay lại trang quản lý đơn hàng
            header("Location: ../../index.php?page=order-list");
            exit();
        } catch (Exception $e) {
            die("Lỗi hệ thống khi cập nhật đơn hàng: " . $e->getMessage());
        }
    }
}

// Nếu truy cập trái phép hoặc dữ liệu sai, đẩy về dashboard
header("Location: ../../index.php?page=dashboard");
exit();