<?php
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$order_id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
</head>
<body>
    <h1>Cảm ơn bạn đã mua hàng!</h1>
    <p>Mã đơn hàng của bạn: <strong><?php echo $order_id; ?></strong></p>
    <p><a href="index.php">Tiếp tục mua sắm</a></p>
</body>
</html>