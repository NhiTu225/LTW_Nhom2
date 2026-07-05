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
        unset($_SESSION['checkout_mode'], $_SESSION['checkout_items']);

        $stock = max(0, intval($book['stock_quantity'] ?? 10));
        if ($stock <= 0) {
            echo 'out_of_stock';
            exit();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $existing_quantity = isset($_SESSION['cart'][$id]['quantity']) ? intval($_SESSION['cart'][$id]['quantity']) : 0;
        if ($existing_quantity + $quantity > $stock) {
            $quantity = max(0, $stock - $existing_quantity);
        }

        if ($quantity <= 0) {
            echo 'out_of_stock';
            exit();
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
    unset($_SESSION['checkout_mode'], $_SESSION['checkout_items']);

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
    unset($_SESSION['checkout_mode'], $_SESSION['checkout_items']);

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
    unset($_SESSION['checkout_mode'], $_SESSION['checkout_items']);
    header("Location: ../index.php");
    exit();
}

// Mặc định nếu chạy bừa bãi thì về trang chủ
header("Location: ../index.php");
exit();
?>