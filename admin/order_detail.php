<?php
session_start();
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit;
}
$order_id = $_GET['id'];

// Lấy thông tin đơn hàng
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Không tìm thấy đơn hàng");
}

// Lấy chi tiết sản phẩm trong đơn
$stmt = $pdo->prepare("
    SELECT oi.*, b.title 
    FROM order_items oi 
    JOIN books b ON oi.book_id = b.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?php echo $order_id; ?></title>
</head>
<body>
    <h1>Chi tiết đơn hàng #<?php echo $order_id; ?></h1>
    <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['fullname']); ?></p>
    <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
    <p><strong>SĐT:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
    <p><strong>Ngày đặt:</strong> <?php echo $order['created_at']; ?></p>
    <h2>Sản phẩm</h2>
    <table border="1" cellpadding="8">
        <tr><th>Tên sách</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['title']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo number_format($item['price']); ?> VNĐ</td>
            <td><?php echo number_format($item['quantity'] * $item['price']); ?> VNĐ</td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="orders.php">Quay lại danh sách đơn hàng</a></p>
</body>
</html>