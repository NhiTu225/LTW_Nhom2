<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (file_exists('../../config/db.php')) {
    include_once '../../config/db.php';
}

$action = $_GET['action'] ?? '';
$id = intval($_GET['id'] ?? 0);

// --- THÊM VÀO GIỎ  ---

if ($action === 'add' && $id > 0) {
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
    
    // Trả về tổng số lượng để JS cập nhật Badge trên Header
    $total_items = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo $total_items; 
    exit();
}

// --- CẬP NHẬT SỐ LƯỢNG GIỎ HÀNG  ---
if ($action === 'update') {
    $quantities = $_POST['quantities'] ?? [];
    if (!empty($quantities) && isset($_SESSION['cart'])) {
        foreach ($quantities as $book_id => $qty) {
            $qty = intval($qty);
            if ($qty <= 0) {
                unset($_SESSION['cart'][$book_id]);
            } else if (isset($_SESSION['cart'][$book_id])) {
                $_SESSION['cart'][$book_id]['quantity'] = $qty;
            }
        }
    }
    // Đẩy về đúng trang giỏ hàng chứ không về trang chủ nữa
    header("Location: ../index.php?page=cart");
    exit();
}

// --- XÓA 1 SẢN PHẨM KHỎI GIỎ ---
if ($action === 'delete' && $id > 0) {
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    // Đẩy về đúng trang giỏ hàng
    header("Location: ../index.php?page=cart");
    exit();
}

// --- XÓA SẠCH SAU KHI ĐẶT HÀNG THÀNH CÔNG ---
if ($action === 'clear_success') {
    unset($_SESSION['cart']);
    header("Location: ../index.php");
    exit();
}

// Mặc định nếu chạy bừa bãi thì về trang chủ
header("Location: ../index.php");
exit();
?>