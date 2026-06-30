<div class="mb-4">
    <a href="index.php?page=book-list" class="btn btn-sm btn-outline-secondary rounded-2 mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h4 class="fw-bold m-0 text-dark">Thêm đầu sách mới</h4>
    <p class="text-muted small m-0">Điền đầy đủ thông tin dưới đây để tạo sản phẩm trong kho hàng</p>
</div>

<div class="card panel-box w-100 p-4 border-0 shadow-sm rounded-3">
    <form action="index.php?page=book-create" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            
            <div class="col-lg-8">
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
                    <label for="description" class="form-label fw-semibold small text-dark">Mô tả nội dung sách</label>
                    <textarea class="form-control rounded-2" id="description" name="description" rows="6" placeholder="Nhập tóm tắt hoặc giới thiệu ngắn về cuốn sách..."></textarea>
                </div>
            </div>

            <div class="col-lg-4 border-start ps-lg-4">
                <label for="image" class="form-label fw-semibold small text-dark">Hình ảnh bìa sách</label>
                
                <div class="mb-3 d-flex justify-content-center bg-light p-3 rounded-3 border style-container">
                    <img id="img-preview" src="https://placehold.co/150x220?text=Preview" 
                         class="rounded-2 border shadow-sm img-fluid" style="max-height: 220px; object-fit: cover;">
                </div>

                <div class="mb-2">
                    <input type="file" class="form-control rounded-2" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                </div>
                <div class="form-text text-muted" style="font-size: 0.75rem;">Định dạng cho phép: .jpg, .png, .jpeg, .webp</div>
            </div>

            <div class="col-12 mt-4 border-top pt-3">
                <div class="d-flex gap-2">
                    <button type="submit" name="btn-add" class="btn btn-primary rounded-3 px-4 py-2">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>Lưu và Đăng bán
                    </button>
                    <a href="index.php?page=book-list" class="btn btn-light border rounded-3 px-3 py-2">Hủy bỏ</a>
                </div>
            </div>

        </div> </form>
</div>

<script>
// Giữ lại cơ chế đổi nguồn ảnh tức thì khi chọn file để Admin xem trước
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