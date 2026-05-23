<?php
// Nhúng kết nối database của nhóm (đã chỉnh lại theo cổng 3307 của máy em)
include '../config/db.php';

if (isset($_POST['btn_submit'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_name = ""; 

    // Xử lý upload ảnh bìa sách
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($ext, $allowed)) {
            $image_name = time() . '_' . $file_name; 
            move_uploaded_file($file_tmp, "uploads/" . $image_name);
        }
    }

    // Lệnh SQL INSERT lưu sách vào bảng 'books' (hoặc em sửa lại đúng tên bảng trong database của nhóm)
    try {
        $sql = "INSERT INTO books (title, author, price, description, image) VALUES (:title, :author, :price, :description, :image)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':title' => $title, 
            ':author' => $author, 
            ':price' => $price, 
            ':description' => $description, 
            ':image' => $image_name
        ]);
        echo "<script>alert('Thêm sách mới thành công!'); window.location='book-add.php';</script>";
    } catch (PDOException $e) {
        echo "Lỗi hệ thống: " . $e->getMessage();
    }
}
// Gọi bộ khung giao diện vào
include 'includes/header.php';
?>

<div class="card p-4 shadow-sm" style="max-width: 700px; margin: 0 auto;">
    <h3 class="mb-4 text-primary"><i class="fa-solid fa-plus me-2"></i>Thêm Sách Mới Vào Kho</h3>
    <form action="book-add.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label fw-bold">Tên cuốn sách</label>
            <input type="text" name="title" class="form-control" placeholder="Nhập tên sách..." required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Tác giả</label>
            <input type="text" name="author" class="form-control" placeholder="Nhập tên tác giả..." required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Giá bán (VNĐ)</label>
            <input type="number" name="price" class="form-control" placeholder="Ví dụ: 50000" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả nội dung</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả cuốn sách..."></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Chọn ảnh bìa sách</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="text-end">
            <button type="submit" name="btn_submit" class="btn btn-success px-4 py-2"><i class="fa-solid fa-save me-2"></i>Lưu sách mới</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>