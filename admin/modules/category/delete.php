<?php
include_once '../../db.php'; // Kết nối Database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    } catch (Exception $e) {
        die("Lỗi xóa danh mục: " . $e->getMessage());
    }
}

// Xóa xong, lập tức quay về trang danh sách danh mục để cập nhật UI mới nhất
header("Location: ../../index.php?page=category-list");
exit();