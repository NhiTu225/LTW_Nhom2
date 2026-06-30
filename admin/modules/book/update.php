<?php
// session_start();

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo "<script>window.location.href='../client/index.php';</script>";
//     exit();
// }

if (file_exists('../config/db.php')) {
    require_once '../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($pdo)) {
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? ''); // Nhận thêm trường tác giả từ form
    $category_id = (!empty($_POST['category_id']) && intval($_POST['category_id']) > 0) ? intval($_POST['category_id']) : null;
    $price = floatval($_POST['price'] ?? 0);
    $sale = intval($_POST['sale'] ?? 0); // LẤY DỮ LIỆU TỪ Ô NHẬP % GIẢM GIÁ MỚI
    $description = trim($_POST['description'] ?? '');
    
    // LOGIC TỰ ĐỘNG TÍNH TOÁN GIÁ CŨ (OLD_PRICE) TRƯỚC KHI LƯU DB
    if ($sale > 0 && $sale < 100) {
        // Ví dụ: Giá bán nhập 120k, sale 20% -> Giá gốc cũ = 120000 / (1 - 0.2) = 150000
        $old_price = $price / (1 - ($sale / 100));
    } else {
        // Nếu không giảm giá (sale = 0), giá gốc cũ bằng chính giá bán hiện tại
        $old_price = $price;
        $sale = 0;
    }

    $old_image = $_POST['old_image'] ?? '';
    $image_name = $old_image;

    // Xử lý upload tệp ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            // Đặt tên ảnh mới mộc mạc bằng time() không lo trùng lặp
            $image_name = time() . '_' . basename($file_name);
            
            // 🌟 4. ĐÃ SỬA: Đường dẫn thư mục ảnh tính từ vị trí file admin/index.php
            $upload_dir = '../public/upload/'; 
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Di chuyển file vào thư mục lưu trữ và xóa ảnh cũ
            if (move_uploaded_file($file_tmp, $upload_dir . $image_name)) {
                if (!empty($old_image) && file_exists($upload_dir . $old_image) && $old_image !== 'default.jpg') {
                    unlink($upload_dir . $old_image);
                }
            }
        }
    }

    if ($id > 0 && !empty($title)) {
        try {
            // CẬP NHẬT CÂU LỆNH SQL: Thêm tác giả, giá cũ (old_price) và phần trăm giảm giá (sale)
            $sql = "UPDATE books 
                    SET title = ?, author = ?, category_id = ?, price = ?, old_price = ?, sale = ?, description = ?, image = ? 
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $author, $category_id, $price, $old_price, $sale, $description, $image_name, $id]);

            echo "<script>window.location.href='index.php?page=book-list';</script>";
            exit();
            
        } catch (Exception $e) {
            die("Lỗi hệ thống khi cập nhật thông tin sách: " . $e->getMessage());
        }
    }
}

echo "<script>window.location.href='index.php?page=book-list';</script>";
exit();