<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (file_exists('../../config/db.php')) {
    include_once '../../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($pdo)) {
    $action = $_POST['action'] ?? '';
    $order_id = intval($_POST['order_id'] ?? 0);

    if ($action === 'confirm_received' && $order_id > 0) {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'received' WHERE id = ? AND user_id = ? AND status = 'completed'");
        $stmt->execute([$order_id, intval($_SESSION['user']['id'] ?? 0)]);
        $_SESSION['success'] = 'Cảm ơn bạn! Đơn hàng đã được xác nhận nhận hàng.';
        header('Location: ../index.php?page=my-orders');
        exit;
    }

    if ($action === 'cancel_order' && $order_id > 0) {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'");
        $stmt->execute([$order_id, intval($_SESSION['user']['id'] ?? 0)]);
        $_SESSION['success'] = 'Đơn hàng đã được hủy thành công.';
        header('Location: ../index.php?page=my-orders');
        exit;
    }
}

header('Location: ../index.php?page=my-orders');
exit;
