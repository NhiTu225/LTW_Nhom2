<?php
include_once '../../db.php'; // Kết nối Database

if (isset($_POST['name'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');

    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
        } catch (Exception $e) {
            die("Lỗi thêm danh mục: " . $e->getMessage());
        }
    }
}

// Thêm xong, lập tức điều hướng quay lại trang hiển thị danh mục của Router trung tâm
header("Location: ../../index.php?page=category-list");
exit();