<?php
require_once 'config/db.php';

// Lấy danh sách sách từ database
$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Sách Nhóm 2</title>
</head>
<body>
    <h1>Danh sách sách</h1>
    <p><a href="cart.php">Xem giỏ hàng</a></p>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Tên sách</th>
            <th>Tác giả</th>
            <th>Giá</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($books as $book): ?>
        <tr>
            <td><?php echo $book['id']; ?></td>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo number_format($book['price']); ?> VNĐ</td>
            <td>
                <a href="cart.php?action=add&id=<?php echo $book['id']; ?>">Thêm vào giỏ</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>