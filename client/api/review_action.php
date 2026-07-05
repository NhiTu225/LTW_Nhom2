<?php
session_start();

// Đi tìm file kết nối cơ sở dữ liệu dựa theo cấu trúc thư mục của em
if (file_exists('../config/db.php')) {
    include_once '../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($pdo)) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = 'Vui lòng đăng nhập để gửi đánh giá!';
        header('Location: ../index.php?page=my-orders');
        exit();
    }

    $book_id = intval($_POST['book_id'] ?? 0);
    $user_id = intval($_SESSION['user']['id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 5);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1) $rating = 1;
    if ($rating > 5) $rating = 5;

    if ($book_id > 0 && !empty($comment)) {
        try {
            $check_order = $pdo->prepare("SELECT 1 FROM orders o JOIN order_items oi ON oi.order_id = o.id WHERE o.user_id = ? AND oi.book_id = ? AND o.status = 'received'");
            $check_order->execute([$user_id, $book_id]);
            if (!$check_order->fetch()) {
                $_SESSION['error'] = 'Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng đã được xác nhận nhận hàng.';
                header('Location: ../index.php?page=my-orders');
                exit();
            }

            $check_review = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND book_id = ?");
            $check_review->execute([$user_id, $book_id]);
            if ($check_review->fetch()) {
                $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này rồi.';
                header('Location: ../index.php?page=my-orders');
                exit();
            }

            $sql = "INSERT INTO reviews (book_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$book_id, $user_id, $rating, $comment]);

            $_SESSION['success'] = 'Cảm ơn bạn đã đánh giá sản phẩm!';
            header('Location: ../index.php?page=my-orders');
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi hệ thống khi gửi đánh giá: ' . $e->getMessage();
            header('Location: ../index.php?page=my-orders');
            exit();
        }
    }
}

header('Location: ../index.php?page=my-orders');
exit();
?>