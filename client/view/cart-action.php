<?php
session_start();

if (file_exists('../../config/db.php')) {
    require_once '../../config/db.php';
} else {
    die("Không tìm thấy kết nối CSDL tại vị trí chỉ định!");
}
// Lấy hành động từ URL (add, update, delete)
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        if (!isset($SESSION['user'])) {
            header("Location: ../auth/login.php?error=Đăng nhập để thêm sản phẩm vào giỏ");
            exit();
        }
        $book_id = intval($_GET['id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1); // Lấy số lượng từ form detail, mặc định là 1

        if ($book_id > 0 && isset($pdo)) {
            // Lấy thông tin sách từ DB để đảm bảo giá tiền chính xác
            $stmt = $pdo->prepare("SELECT title, price, image FROM books WHERE id = ?");
            $stmt->execute([$book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($book) {
                // Nếu giỏ hàng chưa tồn tại, khởi tạo mảng rỗng
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                // Nếu sách đã có trong giỏ, chỉ cần cộng dồn số lượng
                if (isset($_SESSION['cart'][$book_id])) {
                    $_SESSION['cart'][$book_id]['quantity'] += $quantity;
                } else {
                    // Nếu chưa có, thêm mới sản phẩm vào giỏ
                    $_SESSION['cart'][$book_id] = [
                        'title' => $book['title'],
                        'price' => $book['price'],
                        'image' => $book['image'],
                        'quantity' => $quantity
                    ];
                }
            }
        }
        // Thêm xong thì quay lại trang giỏ hàng để kiểm tra
        header("Location: ../index.php?page=cart");
        exit();

    case 'update':
        // Cập nhật số lượng sách trực tiếp trong trang giỏ hàng (giỏ hàng đẩy mảng số lượng lên)
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $id => $qty) {
                $qty = intval($qty);
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$id]); // Số lượng <= 0 thì xóa luôn khỏi giỏ
                } else if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] = $qty;
                }
            }
        }
        header("Location: ../index.php?page=cart");
        exit();

    case 'delete':
        $book_id = intval($_GET['id'] ?? 0);
        if (isset($_SESSION['cart'][$book_id])) {
            unset($_SESSION['cart'][$book_id]); // Xóa cuốn sách này ra khỏi Session
        }
        header("Location: ../index.php?page=cart");
        exit();

    default:
        header("Location: ../index.php");
        exit();
}