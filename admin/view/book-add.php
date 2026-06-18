<div class="mb-4">
    <a href="index.php?page=book-list" class="btn btn-sm btn-outline-secondary rounded-2 mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h4 class="fw-bold m-0 text-dark">Thêm đầu sách mới</h4>
    <p class="text-muted small m-0">Điền đầy đủ thông tin dưới đây để tạo sản phẩm trong kho hàng</p>
</div>

<div class="card panel-box" style="max-width: 700px;">
    <form action="modules/book/create.php" method="POST" enctype="multipart/form-data">
        
        <div class="mb-3">
            <label for="title" class="form-label fw-semibold small text-dark">Tên đầu sách <span class="text-danger">*</span></label>
            <input type="text" class="form-control rounded-2" id="title" name="title" placeholder="Ví dụ: Đắc Nhân Tâm" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="author" class="form-label fw-semibold small text-dark">Tác giả <span class="text-danger">*</span></label>
                <input type="text" class="form-control rounded-2" id="author" name="author" placeholder="Ví dụ: Dale Carnegie" required>
            </div>
            <div class="col-md-6">
                <label for="price" class="form-label fw-semibold small text-dark">Giá bán (₫) <span class="text-danger">*</span></label>
                <input type="number" class="form-control rounded-2" id="price" name="price" placeholder="Ví dụ: 89000" min="0" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label fw-semibold small text-dark">Hình ảnh bìa sách</label>
            <input type="file" class="form-control rounded-2" id="image" name="image" accept="image/*" onchange="previewImage(event)">
            <div class="form-text" style="font-size: 0.75rem;">Định dạng file cho phép: .jpg, .png, .jpeg, .webp</div>
            <div class="mt-2">
                <img id="img-preview" src="https://placehold.co/120x160?text=Preview" class="rounded-2 border shadow-sm" style="width: 110px; height: 150px; object-fit: cover;">
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold small text-dark">Mô tả nội dung sách</label>
            <textarea class="form-control rounded-2" id="description" name="description" rows="4" placeholder="Nhập tóm tắt hoặc giới thiệu ngắn về cuốn sách..."></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="btn-add" class="btn btn-primary rounded-3 px-4 py-2">
                <i class="fa-solid fa-cloud-arrow-up me-2"></i>Lưu và Đăng bán
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