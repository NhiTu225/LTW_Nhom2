<?php
session_start();
require_once 'config/db.php';

// Nếu giỏ hàng trống, chuyển về trang giỏ hàng
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Xử lý khi người dùng gửi form đặt hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    
    // Tính tổng tiền từ giỏ hàng
    $ids = array_keys($_SESSION['cart']);
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total = 0;
        foreach ($books as $book) {
            $total += $book['price'] * $_SESSION['cart'][$book['id']];
        }
        
        // Lưu đơn hàng (tạm thời user_id = 1, sau này khi có đăng nhập sẽ sửa)
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, fullname, address, phone, total_amount) VALUES (1, ?, ?, ?, ?)");
        $stmt->execute([$fullname, $address, $phone, $total]);
        $order_id = $pdo->lastInsertId();
        
        // Lưu chi tiết đơn hàng
        foreach ($books as $book) {
            $quantity = $_SESSION['cart'][$book['id']];
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $book['id'], $quantity, $book['price']]);
        }
        
        // Xóa giỏ hàng
        $_SESSION['cart'] = [];
        
        // Chuyển đến trang cảm ơn hoặc trang đơn hàng
        header("Location: order_success.php?id=$order_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - Nhóm 2</title>
</head>
<body>
    <h1>Thông tin giao hàng</h1>
    <form method="post">
        <p><label>Họ tên: <input type="text" name="fullname" required></label></p>
        <p><label>Địa chỉ: <input type="text" name="address" required></label></p>
        <p><label>Số điện thoại: <input type="text" name="phone" required></label></p>
        <p><button type="submit">Đặt hàng</button></p>
    </form>
    <p><a href="cart.php">Quay lại giỏ hàng</a></p>
</body>
</html>