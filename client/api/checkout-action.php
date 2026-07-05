<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (file_exists('../../config/db.php')) {
    include_once '../../config/db.php';
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id = intval($_GET['id'] ?? $_POST['book_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

if ($action === 'buy' && $id > 0 && isset($pdo)) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($book) {
        $stock = max(0, intval($book['stock_quantity'] ?? 10));
        if ($stock <= 0) {
            header('Location: ../index.php?page=detail&id=' . $id . '&out_of_stock=1');
            exit();
        }

        if ($quantity > $stock) {
            $quantity = $stock;
        }

        if ($quantity <= 0) {
            header('Location: ../index.php?page=detail&id=' . $id . '&out_of_stock=1');
            exit();
        }

        $_SESSION['checkout_mode'] = 'buy_now';
        $_SESSION['checkout_items'] = [[
            'id' => $book['id'],
            'title' => $book['title'],
            'price' => $book['price'],
            'image' => $book['image'],
            'quantity' => $quantity
        ]];
    }
}

header('Location: ../index.php?page=checkout');
exit();
