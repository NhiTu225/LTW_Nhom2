<?php
// Bốc thông tin sách cần sửa từ DB dựa vào tham số ID trên URL
$book = null;
if (isset($_GET['id']) && isset($pdo)) {
    try {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Nếu nhập bừa ID trên URL mà không có sách thực tế, đá bay về danh sách
        if (!$book) {
            echo "<script>alert('Không tìm thấy cuốn sách yêu cầu!'); window.location.href='index.php?page=book-list';</script>";
            exit();
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger mb-3'>Lỗi tải thông tin sách: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<script>window.location.href='index.php?page=book-list';</script>";
    exit();
}
?>

<div class="mb-4">
    <a href="index.php?page=book-list" class="btn btn-sm btn-outline-secondary rounded-2 mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h4 class="fw-bold m-0 text-dark">Chỉnh sửa thông tin sách</h4>
    <p class="text-muted small m-0">Cập nhật lại các thông số hoặc hình ảnh của cuốn sách hệ thống</p>
</div>

<div class="card panel-box" style="max-width: 700px;">
    <form action="modules/book/update.php" method="POST" enctype="multipart/form-data">
        
        <input type="hidden" name="id" value="<?= $book['id'] ?>">

        <div class="mb-3">
            <label for="title" class="form-label fw-semibold small text-dark">Tên đầu sách <span class="text-danger">*</span></label>
            <input type="text" class="form-control rounded-2" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="author" class="form-label fw-semibold small text-dark">Tác giả <span class="text-danger">*</span></label>
                <input type="text" class="form-control rounded-2" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="price" class="form-label fw-semibold small text-dark">Giá bán (₫) <span class="text-danger">*</span></label>
                <input type="number" class="form-control rounded-2" id="price" name="price" value="<?= $book['price'] ?>" min="0" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small text-dark d-block">Hình ảnh bìa sách hiện tại</label>
            <div class="mb-2">
                <img id="img-preview" src="../public/upload/<?= htmlspecialchars($book['image'] ?? 'default.jpg') ?>" 
                     class="rounded-2 border shadow-sm" style="width: 110px; height: 150px; object-fit: cover;"
                     onerror="this.src='https://placehold.co/110x150?text=No+Cover'">
            </div>
            <label for="image" class="form-label fw-semibold small text-muted">Chọn ảnh mới nếu muốn thay đổi:</label>
            <input type="file" class="form-control rounded-2" id="image" name="image" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold small text-dark">Mô tả nội dung sách</label>
            <textarea class="form-control rounded-2" id="description" name="description" rows="4"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="btn-edit" class="btn btn-success rounded-3 px-4 py-2">
                <i class="fa-solid fa-square-check me-2"></i>Cập nhật thay đổi
            </button>
            <a href="index.php?page=book-list" class="btn btn-light border rounded-3 px-3 py-2">Hủy bỏ</a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('img-preview');
        output.src = reader.result;
    }
    if(event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>