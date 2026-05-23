<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị Hệ Thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { min-width: 240px; max-width: 240px; background-color: #212529; color: white; min-height: 100vh; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover { background-color: #343a40; color: white; }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3">
        <a href="index.php" class="text-white text-decoration-none mb-3 fs-4"><i class="fa-solid fa-user-gear me-2"></i>Admin Panel</a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="index.php"><i class="fa-solid fa-chart-line me-2"></i> Tổng quan</a></li>
            <li><a href="book-add.php"><i class="fa-solid fa-book me-2"></i> Quản lý Sách (CRUD)</a></li>
        </ul>
    </div>
    <div class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3">
            <span class="navbar-brand mb-0 h1">Hệ Thống Quản Lý Cửa Hàng Sách</span>
        </nav>
        <div class="p-4">