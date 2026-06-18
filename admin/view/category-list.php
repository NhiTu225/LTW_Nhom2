<?php
$categories = [];
if (isset($pdo)) {
    try {
        // Lấy danh sách danh mục từ Database (Giả sử bảng tên là categories)
        $categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "<div class='alert alert-danger mb-3'>Lỗi lấy danh mục: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="mb-4">
    <h4 class="fw-bold m-0 text-dark">Quản lý danh mục sách</h4>
    <p class="text-muted small m-0">Phân loại các thể loại sách có trong cửa hàng</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card panel-box">
            <h6 class="fw-bold text-dark mb-3">Thêm danh mục mới</h6>
            <form action="modules/category/create.php" method="POST">
                <div class="mb-3">
                    <label for="category_name" class="form-label small fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-2" id="category_name" name="name" placeholder="Ví dụ: Tiểu thuyết, Kỹ năng..." required>
                </div>
                <div class="mb-3">
                    <label for="category_desc" class="form-label small fw-semibold">Mô tả ngắn</label>
                    <textarea class="form-control rounded-2" id="category_desc" name="description" rows="3" placeholder="Mô tả thể loại này..."></textarea>
                </div>
                <button type="submit" name="btn-add-cat" class="btn btn-primary rounded-3 w-100">
                    <i class="fa-solid fa-plus me-1"></i> Thêm thể loại
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card panel-box">
            <h6 class="fw-bold text-dark mb-3">Tất cả danh mục</h6>
            <div class="table-responsive">
                <table class="table align-middle m-0 table-hover small">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th style="width: 120px; text-align: center;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Chưa có danh mục nào được tạo.</td></tr>
                        <?php else: ?>
                            <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td class="fw-bold text-secondary">#<?= $cat['id'] ?></td>
                                <td><span class="fw-bold text-dark"><?= htmlspecialchars($cat['name']) ?></span></td>
                                <td class="text-muted"><?= htmlspecialchars($cat['description'] ?? 'Không có mô tả') ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="modules/category/delete.php?id=<?= $cat['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-2 px-2"
                                           onclick="return confirm('Xóa danh mục có thể ảnh hưởng đến các sách thuộc thể loại này! Bạn chắc chắn muốn xóa chứ?');">
                                            <i class="fa-solid fa-trash"></i> Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>