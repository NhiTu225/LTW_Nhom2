<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (file_exists('../../config/db.php')) {
    include_once '../../config/db.php';
}

$action = $_GET['action'] ?? '';
$id = intval($_GET['id'] ?? 0);

// --- MUA 1 SẢN PHẨM  ---

if ($action === 'buy' && $id > 0) {
    $quantity = intval($_POST['quantity'] ?? 1);
    
    // Lấy thông tin sách từ DB
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($book) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $book['id'],
                'title' => $book['title'],
                'price' => $book['price'],
                'image' => $book['image'],
                'quantity' => $quantity
            ];
        }
    }
    
    header("Location: ../index.php?page=checkout");
    exit();
}


// Mặc định nếu chạy bừa bãi thì về trang chủ
header("Location: ../index.php");
exit();
?>