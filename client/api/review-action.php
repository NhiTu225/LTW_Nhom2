<?php
session_start();

// Đi tìm file kết nối cơ sở dữ liệu dựa theo cấu trúc thư mục của em
if (file_exists('../config/db.php')) {
    include_once '../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($pdo)) {
    // Kiểm tra đăng nhập qua session user của hệ thống
    if (!isset($_SESSION['user'])) {
        echo "<script>alert('Vui lòng đăng nhập để gửi đánh giá!'); window.history.back();</script>";
        exit();
    }

    $book_id = intval($_POST['book_id'] ?? 0);
    $user_id = intval($_SESSION['user']['id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 5);
    $comment = trim($_POST['comment'] ?? '');

    // Giới hạn số sao từ 1 đến 5 cho an toàn
    if ($rating < 1) $rating = 1;
    if ($rating > 5) $rating = 5;

    if ($book_id > 0 && !empty($comment)) {
        try {
            $sql = "INSERT INTO reviews (book_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$book_id, $user_id, $rating, $comment]);

            echo "<script>alert('Cảm ơn bạn đã đánh giá sản phẩm!'); window.history.back();</script>";
            exit();
        } catch (Exception $e) {
            die("Lỗi hệ thống khi gửi đánh giá: " . $e->getMessage());
        }
    }
}

echo "<script>window.history.back();</script>";
exit();
?>