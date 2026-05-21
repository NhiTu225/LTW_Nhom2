<?php
require_once "../config/db.php";

$sql = "
    SELECT 
        books.id,
        books.title,
        books.author,
        books.price,
        books.stock,
        books.status,
        books.image,
        categories.name AS category_name
    FROM books
    LEFT JOIN categories ON books.category_id = categories.id
    ORDER BY books.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý sách - BookStore Admin</title>
    <link rel="stylesheet" href="../style.css">
  </head>
  <body>
    <aside class="sidebar">
      <div class="brand">
        <div class="brand-icon">📚</div><b>BookStore Admin</b>
      </div>
        <nav>
          <a class="nav-item active" href="/">
            <span>📊</span><span>Dashboard</span>
          </a>
          <a class="nav-item " href="/view/books.php">
            <span>📚</span><span>Quản lý sách</span>
          </a>
          <a class="nav-item " href="/view/categories.php">
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
        <h1>Quản lý sách</h1>
        <p>Quản lý danh sách sản phẩm sách</p>
      </section>
      <div class="toolbar">
        <div class="filter">🔎<input placeholder="Tìm kiếm theo tên sách hoặc tác giả..."></div><select>
          <option>Tất cả danh mục</option>
          <option>Kỹ năng sống</option>
          <option>Văn học</option>
        </select><a class="btn primary" href="book-form.php">+ Thêm sách</a>
      </div>
      <div class="panel no-pad">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Sách</th>
                <th>Tác giả</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Kho</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($books)): ?>
                  <?php foreach ($books as $book): ?>
                      <tr>
                          <td>
                              <div class="book-cell">
                                  <img 
                                      src="<?= htmlspecialchars($book["image"] ?: "https://via.placeholder.com/200x300") ?>" 
                                      alt="<?= htmlspecialchars($book["title"]) ?>"
                                  >

                                  <div>
                                      <b><?= htmlspecialchars($book["title"]) ?></b>
                                      <small>ID: <?= htmlspecialchars($book["id"]) ?></small>
                                  </div>
                              </div>
                          </td>

                          <td>
                              <?= htmlspecialchars($book["author"]) ?>
                          </td>

                          <td>
                              <?= htmlspecialchars($book["category_name"] ?? "Chưa có danh mục") ?>
                          </td>

                          <td>
                              <?= number_format($book["price"], 0, ",", ".") ?> ₫
                          </td>

                          <td>
                              <?= htmlspecialchars($book["stock"]) ?>
                          </td>

                          <td>
                              <?php if ($book["status"] === "active"): ?>
                                  <span class="badge active">Đang hoạt động</span>
                              <?php else: ?>
                                  <span class="badge">Tạm ẩn</span>
                              <?php endif; ?>
                          </td>

                          <td>
                              <button class="icon-btn">
                                  <a href="book-form.php?id=<?= $book["id"] ?>">Edit</a>
                              </button>

                              <button class="icon-btn">
                                  <a 
                                      href="delete-book.php?id=<?= $book["id"] ?>" 
                                      onclick="return confirm('Bạn có chắc muốn xóa sách này không?')"
                                  >
                                      Delete
                                  </a>
                              </button>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              <?php else: ?>
                  <tr>
                      <td colspan="7" style="text-align: center;">
                          Chưa có sách nào trong database.
                      </td>
                  </tr>
              <?php endif; ?>
          </tbody>
          </table>
        </div>
      </div>
      <div class="pagination">
        <button>Trước</button>
        <button class="active">1</button>
        <button>2</button>
        <button>3</button>
        <button>Sau</button>
    </div>
    </main>
  </body>
</html>