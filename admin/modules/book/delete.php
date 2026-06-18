<?php
$admin_path = dirname(__DIR__, 2);
$db_admin = $admin_path . DIRECTORY_SEPARATOR . 'db.php';
$db_config = dirname($admin_path) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db.php';

if (file_exists($db_admin)) {
    include_once $db_admin;
} else if (file_exists($db_config)) {
    include_once $db_config;
}

if (!isset($pdo)) {
    die("Hệ thống không tìm thấy biến kết nối PDO (\$pdo)!");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // 1. Tìm tên file ảnh bìa vật lý để dọn dẹp thư mục upload
        $stmt_img = $pdo->prepare("SELECT image FROM books WHERE id = ?");
        $stmt_img->execute([$id]);
        $img_name = $stmt_img->fetchColumn();
        
        if ($img_name && $img_name != 'default.jpg' && file_exists("../../../public/upload/" . $img_name)) {
            unlink("../../../public/upload/" . $img_name);
        }

        // 2. Thực thi xóa bản ghi khỏi cơ sở dữ liệu qua PDO
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);

    } catch (Exception $e) {
        die("Lỗi Database khi xóa sách: " . $e->getMessage());
    }
}

header("Location: ../../index.php?page=book-list");
exit();