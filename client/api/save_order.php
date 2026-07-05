<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    $data = [];
}

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Không thể kết nối cơ sở dữ liệu.']);
    exit;
}

$checkout_mode = $_SESSION['checkout_mode'] ?? '';
$checkout_items = $_SESSION['checkout_items'] ?? [];
$items_to_order = [];

if ($checkout_mode === 'buy_now' && !empty($checkout_items)) {
    $items_to_order = $checkout_items;
} else {
    $items_to_order = $_SESSION['cart'] ?? [];
}

if (empty($items_to_order)) {
    echo json_encode(['success' => false, 'message' => 'Giỏ hàng đang trống.']);
    exit;
}

$user_id = $_SESSION['user']['id'] ?? 0;
$fullname = trim($data['fullname'] ?? '');
$phone = trim($data['phone'] ?? '');
$address = trim($data['address'] ?? '');
$total = floatval($data['total'] ?? 0);

if ($fullname === '' || $phone === '' || $address === '' || $total <= 0) {
    echo json_encode(['success' => false, 'message' => 'Thông tin giao hàng không hợp lệ.']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, fullname, address, phone, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([$user_id, $fullname, $address, $phone, $total]);
    $order_id = $pdo->lastInsertId();

    foreach ($items_to_order as $item) {
        if (empty($item['id'])) {
            continue;
        }

        $book_id = intval($item['id']);
        $qty = intval($item['quantity'] ?? 1);
        $stmt_stock = $pdo->prepare("SELECT id, stock_quantity FROM books WHERE id = ?");
        $stmt_stock->execute([$book_id]);
        $book_stock = $stmt_stock->fetch(PDO::FETCH_ASSOC);

        if (!$book_stock) {
            throw new Exception('Một sản phẩm trong giỏ hàng không còn tồn tại.');
        }

        $available_stock = max(0, intval($book_stock['stock_quantity'] ?? 0));
        if ($available_stock < $qty) {
            throw new Exception('Số lượng đặt hàng vượt quá tồn kho hiện có.');
        }

        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt_item->execute([
            $order_id,
            $book_id,
            $qty,
            floatval($item['price'] ?? 0)
        ]);

        $stmt_update = $pdo->prepare("UPDATE books SET stock_quantity = stock_quantity - ? WHERE id = ?");
        $stmt_update->execute([$qty, $book_id]);
    }

    $pdo->commit();
    if ($checkout_mode === 'buy_now') {
        unset($_SESSION['checkout_mode'], $_SESSION['checkout_items']);
    } else {
        unset($_SESSION['cart']);
        unset($_SESSION['checkout_mode'], $_SESSION['checkout_items']);
    }
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>