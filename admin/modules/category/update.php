<?php
session_start();

// Chốt chặn bảo mật admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../client/index.php");
    exit();
}

if (file_exists('../../../config/db.php')) {
    require_once '../../../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $category_name = trim($_POST['name'] ?? '');

    if ($category_id > 0 && !empty($category_name) && isset($pdo)) {
        try {
            // Cập nhật tên danh mục mới dựa trên ID
            // Bạn lưu ý kiểm tra lại tên cột trong DB của bạn (ví dụ: name hay category_name)
            $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$category_name, $category_id]);

            // Cập nhật thành công, quay về danh sách danh mục
            header("Location: ../../index.php?page=category-list");
            exit();
        } catch (Exception $e) {
            die("Lỗi hệ thống khi cập nhật danh mục: " . $e->getMessage());
        }
    }
}

header("Location: ../../index.php?page=category-list");
exit();