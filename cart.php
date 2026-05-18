<?php
session_start();
require_once 'config/db.php';

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý thêm sản phẩm vào giỏ
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $book_id = (int)$_GET['id'];
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]++;
    } else {
        $_SESSION['cart'][$book_id] = 1;
    }
    // Chuyển hướng về trang giỏ hàng để tránh thêm nhiều lần khi refresh
    header('Location: cart.php');
    exit;
}

// Xử lý xóa sản phẩm (tùy chọn thêm sau)
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $book_id = (int)$_GET['id'];
    unset($_SESSION['cart'][$book_id]);
    header('Location: cart.php');
    exit;
}

// Lấy danh sách sản phẩm trong giỏ kèm thông tin từ database
$cart_items = [];
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM books WHERE id IN ($ids)");
    $books_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($books_data as $book) {
        $book_id = $book['id'];
        $quantity = $_SESSION['cart'][$book_id];
        $cart_items[] = [
            'id' => $book_id,
            'title' => $book['title'],
            'price' => $book['price'],
            'quantity' => $quantity,
            'subtotal' => $book['price'] * $quantity
        ];
        $total_price += $book['price'] * $quantity;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - Nhóm 2</title>
</head>
<body>
    <h1>Giỏ hàng của bạn</h1>
    <p><a href="index.php">Tiếp tục mua sắm</a></p>
    <?php if (empty($cart_items)): ?>
        <p>Giỏ hàng trống.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Tên sách</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Xóa</th>
            </tr>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?php echo $item['title']; ?></td>
                <td><?php echo number_format($item['price']); ?> VNĐ</td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo number_format($item['subtotal']); ?> VNĐ</td>
                <td><a href="cart.php?action=remove&id=<?php echo $item['id']; ?>">Xóa</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" align="right"><strong>Tổng cộng:</strong></td>
                <td><strong><?php echo number_format($total_price); ?> VNĐ</strong></td>
                <td></td>
            </tr>
        </table>
        <p><a href="checkout.php">Tiến hành thanh toán</a></p>
    <?php endif; ?>
</body>
</html>