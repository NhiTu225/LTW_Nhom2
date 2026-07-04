<?php
// 1. Dùng đường dẫn tuyệt đối an toàn để nạp file db.php của bạn
$admin_path = dirname(__DIR__, 2);
$db_admin = $admin_path . DIRECTORY_SEPARATOR . 'db.php';
$db_config = dirname($admin_path) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db.php';


if (file_exists($db_admin)) {
    include_once $db_admin;
} else if (file_exists($db_config)) {
    include_once $db_config;
}


// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo "<script>window.location.href='../client/index.php';</script>";
//     exit();
// }

// Kiểm tra sự tồn tại của biến $pdo từ file db.php của bạn
if (!isset($pdo)) {
    die("Hệ thống không tìm thấy biến kết nối PDO (\$pdo). Vui lòng kiểm tra lại đường dẫn file db.php!");
}

// 2. Xử lý khi nhận dữ liệu Form submit lên
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $price = intval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $image_name = trim($_POST['image'] ?? '');
    if (empty($image_name)) {
        $image_name = 'default.jpg';
    }
    $sale_start = !empty($_POST['sale_start']) ? $_POST['sale_start'] : null;
    $sale_end = !empty($_POST['sale_end']) ? $_POST['sale_end'] : null;
    $sale = intval($_POST['sale'] ?? 0);

    // 3. Xử lý logic Upload ảnh bìa sách vào public/upload/
    // if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    //     $target_dir = "../../../public/upload/"; 
    //     $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    //     $image_name = time() . '_' . uniqid() . '.' . $ext;
    //     $target_file = $target_dir . $image_name;

    //     if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
    //         $image_name = 'default.jpg'; 
    //     }
    // }

    // 4. Chèn dữ liệu vào bảng books bằng cú pháp PDO chuẩn
    if (!empty($title) && !empty($author)) {
        try {
            $sql = "INSERT INTO books (title, author, price, image, description, sale, sale_start, sale_end) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $author, $price, $image_name, $description, $sale, $sale_start, $sale_end]);
        } catch (Exception $e) {
            die("Lỗi Database khi thêm sách: " . $e->getMessage());
        }
    }
}

// 5. Điều hướng mượt mà quay lại trang danh sách
echo "<script>window.location.href='index.php?page=book-list';</script>";
exit();