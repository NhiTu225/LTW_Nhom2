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
                    <div class="col-md-3">
                        <label for="author" class="form-label fw-semibold small text-dark">Tác giả <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-2" id="author" name="author" placeholder="Ví dụ: Dale Carnegie" required>
                    </div>

                    <div class="col-md-3">
                        <label for="category_id" class="form-label fw-semibold small text-dark">Danh mục sách <span class="text-danger">*</span></label>
                        <select class="form-select rounded-2" id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php
                            if (isset($pdo)) {
                                $stmt_cat = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
                                while ($cat = $stmt_cat->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['name']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="price" class="form-label fw-semibold small text-dark">Giá bán (₫) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control rounded-2" id="price" name="price" placeholder="Ví dụ: 89000" min="0" required>
                    </div>

                    <div class="col-md-3">
                        <label for="sale" class="form-label fw-semibold small text-dark">Giảm giá (%)</label>
                        <input type="number" class="form-control rounded-2" id="sale" name="sale" value="0" min="0" max="100" placeholder="Ví dụ: 20">
                    </div>

                    <div class="col-md-3">
                        <label for="stock_quantity" class="form-label fw-semibold small text-dark">Tồn kho</label>
                        <input type="number" class="form-control rounded-2" id="stock_quantity" name="stock_quantity" value="10" min="0" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="sale_start" class="form-label fw-semibold small text-dark">Bắt đầu giảm giá</label>
                        <input type="datetime-local" class="form-control rounded-2" id="sale_start" name="sale_start">
                    </div>
                    <div class="col-md-6">
                        <label for="sale_end" class="form-label fw-semibold small text-dark">Kết thúc giảm giá</label>
                        <input type="datetime-local" class="form-control rounded-2" id="sale_end" name="sale_end">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold small text-dark">Mô tả nội dung sách</label>
                    <textarea class="form-control rounded-2" id="description" name="description" rows="6" placeholder="Nhập tóm tắt hoặc giới thiệu ngắn về cuốn sách..."></textarea>
                </div>
            </div>

            <div class="col-lg-4 border-start ps-lg-4">
                <label class="form-label">Link ảnh bìa sách (URL):</label>
                <input type="text" name="image" class="form-control" value="<?php echo $book['image'] ?? ''; ?>" placeholder="Dán link ảnh từ Google vào đây...">

                <!-- Gợi ý: Có thể thêm một đoạn script nhỏ để hiển thị ảnh preview ngay lập tức khi dán link -->
                <img id="preview-img" src="<?php echo $book['image'] ?? ''; ?>" style="max-width: 150px; margin-top: 10px;">
                <script>
                    // Script để ảnh hiển thị ngay khi dán link
                    document.querySelector('input[name="image"]').addEventListener('input', function(e) {
                        document.getElementById('preview-img').src = e.target.value;
                    });
                </script>
            </div>

            <div class="col-12 mt-4 border-top pt-3">
                <div class="d-flex gap-2">
                    <button type="submit" name="btn-add" class="btn btn-primary rounded-3 px-4 py-2">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>Lưu và Đăng bán
                    </button>
                    <a href="index.php?page=book-list" class="btn btn-light border rounded-3 px-3 py-2">Hủy bỏ</a>
                </div>
            </div>

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