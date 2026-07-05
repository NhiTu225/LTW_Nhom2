<?php
session_start();
// Chốt chặn bảo mật nếu cần
$admin_role = $_SESSION['user']['role'] ?? $_SESSION['role'] ?? '';
if ($admin_role !== 'admin') {
    header("Location: ../../../client/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists('../../../config/db.php')) {
        require_once '../../../config/db.php';
    }

    $order_id = intval($_POST['order_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $status_map = [
        'Chờ xác nhận' => 'pending',
        'Đang xử lý' => 'processing',
        'Đang giao' => 'shipping',
        'Đã hoàn thành' => 'completed',
        'Đã hủy' => 'cancelled',
        'pending' => 'pending',
        'processing' => 'processing',
        'shipping' => 'shipping',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
    ];
    $status_value = $status_map[$status] ?? 'pending';

    if ($order_id > 0 && !empty($status) && isset($pdo)) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status_value, $order_id]);
            
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