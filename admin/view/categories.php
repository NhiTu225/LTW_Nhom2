<?php
require_once "../config/db.php";

$sql = "SELECT * FROM categories ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý danh mục - BookStore Admin</title>
    <link rel="stylesheet" href="../style.css">
  </head>
  <body>
    <aside class="sidebar">
      <div class="brand">
        <div class="brand-icon">📚</div><b>BookStore Admin</b>
      </div>
      <nav>
        <a class="nav-item " href=" /">
          <span>📊</span><span>Dashboard</span>
        </a>
        <a class="nav-item " href="books.php">
          <span>📚</span><span>Quản lý sách</span>
        </a>
        <a class="nav-item active" href="categories.php">
          <span>📁</span><span>Danh mục</span>
        </a>
      </nav>
      <div class="version"><b>Phiên bản</b><small>Admin Dashboard v1.0</small></div>
    </aside>
    <header class="topbar">
      <div class="search">🔎<input placeholder="Tìm kiếm sách, đơn hàng, khách hàng..."></div>
      <div class="account"><button class="bell">🔔<span></span></button>
        <div class="admin">
          <p>Admin User</p><small>admin@bookstore.com</small>
        </div>
        <div class="avatar">👤</div>
      </div>
    </header>
    <main class="main">
      <section class="page-title">
        <h1>Quản lý danh mục</h1>
        <p>Phân loại sách trong cửa hàng</p>
      </section>
      <div class="between">
        <div></div><button class="btn primary">+ Thêm danh mục</button>
      </div>
      <div class="category-grid">
        <div class="category-card">
          <div>
            <h3>Vidu112312312</h3>
            <p>Vidu112312312</p><span class="badge active">Vidu112312312</span>
          </div>
          <div class="cat-icon">📁</div>
          <div class="card-actions"><button>✏️ Sửa</button><button class="danger">🗑️ Xóa</button></div>
        </div>
        <div class="category-card">
          <?php foreach ($categories as $category): ?>
        <div class="category-card">
            <div>
                <h3>
                    <?= htmlspecialchars($category["name"]) ?>
                </h3>

                <p>
                    <?= htmlspecialchars($category["total_books"]) ?> cuốn sách
                </p>

                <span class="badge active">
                    <?= $category["status"] === "active" ? "Đang hoạt động" : "Tạm ẩn" ?>
                </span>
            </div>

            <div class="cat-icon">📁</div>

            <div class="card-actions">
                <button>✏️ Sửa</button>
                <button class="danger">🗑️ Xóa</button>
            </div>
        </div>
    <?php endforeach; ?>
        </div>
      </div>
      <div class="panel form narrow">
        <h2>Thêm danh mục mới</h2><label>Tên danh mục<input placeholder="Nhập tên danh mục"></label><label>Mô tả<textarea rows="3"></textarea></label>
        <div class="actions"><button class="btn">Hủy</button><button class="btn primary">Lưu danh mục</button></div>
      </div>
    </main>
  </body>
</html>
