<?php
session_start();

// Nhúng file kết nối database
if (file_exists('../config/db.php')) {
    require_once '../config/db.php';
}

$action = $_GET['action'] ?? '';

switch ($action) {
    // 1. HÀNH ĐỘNG: THÊM SÁCH VÀO GIỎ HÀNG
    case 'add':
        $book_id = intval($_GET['id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);

        if ($book_id > 0 && isset($pdo)) {
            // Lấy thông tin sách để check giá
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($book) {
                // Nếu giỏ hàng chưa tồn tại, khởi tạo mảng rỗng
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                // Nếu sách đã có trong giỏ, tăng số lượng
                if (isset($_SESSION['cart'][$book_id])) {
                    $_SESSION['cart'][$book_id]['quantity'] += $quantity;
                } else {
                    // Nếu chưa có, thêm mới vào giỏ
                    $_SESSION['cart'][$book_id] = [
                        'title' => $book['title'],
                        'price' => $book['price'],
                        'image' => $book['image'] ?? '',
                        'quantity' => $quantity
                    ];
                }
            }
        }
        // Thêm xong quay lại trang giỏ hàng của client
        header("Location: cart.php");
        exit();

    // 2. HÀNH ĐỘNG: XÓA SÁCH KHỎI GIỎ HÀNG
    case 'delete':
        $book_id = intval($_GET['id'] ?? 0);
        if (isset($_SESSION['cart'][$book_id])) {
            unset($_SESSION['cart'][$book_id]);
        }
        header("Location: cart.php");
        exit();

    // 3. HÀNH ĐỘNG: ĐẶT HÀNG (LƯU VÀO DATABASE)
    case 'checkout':
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, bắt ra trang login
            header("Location: auth/login.php");
            exit();
        }

        if (empty($_SESSION['cart']) || !isset($pdo)) {
            header("Location: index.php");
            exit();
        }

        // Tính tổng tiền giỏ hàng hiện tại
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        try {
            $pdo->beginTransaction();

            // 3.1 Thêm vào bảng orders (Bạn check lại tên cột total_amount hay total_price nhé)
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'Chờ xác nhận', NOW())");
            $stmt->execute([$_SESSION['user_id'], $total_amount]);
            $order_id = $pdo->lastInsertId();

            // 3.2 Thêm vào bảng chi tiết đơn hàng (order_details) nếu hệ thống của bạn có bảng này
            if ($order_id) {
                $stmtDetail = $pdo->prepare("INSERT INTO order_details (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
                foreach ($_SESSION['cart'] as $book_id => $item) {
                    $stmtDetail->execute([$order_id, $book_id, $item['quantity'], $item['price']]);
                }
            }

            $pdo->commit();

            // Đặt hàng thành công -> Xóa sạch giỏ hàng Session
            unset($_SESSION['cart']);
            
            // Chuyển hướng đến trang thông báo thành công hoặc lịch sử mua hàng
            echo "<script>alert('Đặt hàng thành công! Mã đơn hàng của bạn là #".$order_id."'); window.location.href='index.php';</script>";
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            die("Lỗi xử lý đặt hàng: " . $e->getMessage());
        }
        break;

    default:
        header("Location: index.php");
        exit();
}