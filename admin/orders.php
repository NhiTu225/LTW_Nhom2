<?php
session_start();
require_once '../config/db.php';

// (Tạm thời bỏ qua kiểm tra đăng nhập, sau này nhóm sẽ thêm)
// Chỉ cần lấy danh sách đơn hàng từ database
$stmt = $pdo->query("
    SELECT o.*, u.username 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng - Admin</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Danh sách đơn hàng</h1>
    <table>
        <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Địa chỉ</th>
            <th>Số điện thoại</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th>Chi tiết</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo htmlspecialchars($order['fullname']); ?></td>
            <td><?php echo htmlspecialchars($order['address']); ?></td>
            <td><?php echo htmlspecialchars($order['phone']); ?></td>
            <td><?php echo number_format($order['total_amount']); ?> VNĐ</td>
            <td><?php echo $order['status']; ?></td>
            <td><?php echo $order['created_at']; ?></td>
            <td><a href="order_detail.php?id=<?php echo $order['id']; ?>">Xem</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="index.php">Về trang admin</a></p>
</body>
</html>