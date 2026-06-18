<?php
session_start();

// Chốt chặn quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../client/index.php");
    exit();
}

if (file_exists('../../../config/db.php')) {
    require_once '../../../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($pdo)) {
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    
    // Giữ lại tên ảnh cũ phòng trường hợp Admin không upload ảnh mới thay thế
    $old_image = $_POST['old_image'] ?? '';
    $image_name = $old_image;

    // Xử lý upload tệp ảnh mới (nếu có)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        
        // Tách đuôi mở rộng để kiểm tra tính hợp pháp (tránh hacker upload file .php)
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            // Đặt tên file độc nhất tránh trùng lặp
            $image_name = time() . '_' . $file_name;
            $upload_dir = '../../../public/uploads/'; // Đường dẫn đến thư mục chứa tài nguyên ảnh
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Di chuyển file vào thư mục lưu trữ
            move_uploaded_file($file_tmp, $upload_dir . $image_name);
        }
    }

    if ($id > 0 && !empty($title)) {
        try {
            // Thực hiện câu lệnh Cập nhật thông tin Sách dựa trên ID
            $sql = "UPDATE books SET title = ?, category_id = ?, price = ?, description = ?, image = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $category_id, $price, $description, $image_name, $id]);

            header("Location: ../../index.php?page=book-list");
            exit();
        } catch (Exception $e) {
            die("Lỗi hệ thống khi cập nhật thông tin sách: " . $e->getMessage());
        }
    }
}

header("Location: ../../index.php?page=book-list");
exit();