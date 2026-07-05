<?php
session_start();
// Điều chỉnh đường dẫn tới file kết nối database của bạn
require_once '../../config/db.php'; 

if (isset($_GET['action']) && $_GET['action'] == 'buy') {
    // 1. Kiểm tra giỏ hàng và đăng nhập
    if (empty($_SESSION['cart'])) {
        die("Giỏ hàng trống!");
    }
    
    // Nếu bạn bắt buộc phải đăng nhập mới được mua
    if (!isset($_SESSION['user'])) {
        header('Location: ../login.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 2. Lấy thông tin user và tổng tiền
        $user_id = $_SESSION['user']['id'];
        $total_money = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_money += $item['price'] * $item['quantity'];
        }

        // 3. Insert vào bảng orders
        $sql_order = "INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'Chờ xác nhận', NOW())";
        $stmt_order = $pdo->prepare($sql_order);
        $stmt_order->execute([$user_id, $total_money]);
        
        $order_id = $pdo->lastInsertId(); // Lấy ID đơn hàng vừa tạo

        // 4. Insert chi tiết vào bảng order_items
        $sql_item = "INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_item = $pdo->prepare($sql_item);

        foreach ($_SESSION['cart'] as $item) {
            $stmt_item->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
        }

        $pdo->commit(); // Lưu tất cả vào DB

        // 5. Xóa giỏ hàng sau khi đặt thành công
        unset($_SESSION['cart']);
        
        echo "<script>alert('Đặt hàng thành công! Mã đơn hàng của bạn là #$order_id'); window.location.href='../../index.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Lỗi hệ thống: " . $e->getMessage();
    }
}
?>