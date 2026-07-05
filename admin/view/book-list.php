
<?php
$books = [];
if (isset($pdo)) {
    try {
        $books = $pdo->query("SELECT * FROM books ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Lỗi lấy danh sách sách: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold m-0 text-dark">Danh sách kho sách</h4>
        <p class="text-muted small m-0">Quản lý các đầu sách hiện có trên hệ thống cửa hàng</p>
    </div>
    <a href="index.php?page=book-add" class="btn btn-primary rounded-3 px-3.5 py-2 small">
        <i class="fa-solid fa-plus me-2"></i>Thêm sách mới
    </a>
</div>

<div class="card panel-box">
    <div class="table-responsive">
        <table class="table align-middle m-0 table-hover">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th style="width: 80px;">Bìa sách</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Giá bán</th>
                    <th class="text-center">Tồn kho</th>
                    <th style="width: 160px; text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($books)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Kho hàng trống rỗng. Hãy thêm sách mới!</td></tr>
                <?php else: ?>
                    <?php 
                    $stt = 1;
                    foreach ($books as $b): 
                    ?>
                    <tr>
                        <!-- Hiển thị STT theo thứ tự -->
                        <td class="fw-bold text-secondary">#<?= $stt++ ?></td> 
                        
                        <td>
                            <?php 
                            $image_path = !empty($b['image']) ? $b['image'] : 'https://placehold.co/50x70?text=No+Img';
                            $img_src = (strpos($image_path, 'http') === 0) ? $image_path : '../public/images/' . $image_path;
                            ?>
                            <img src="<?php echo $img_src; ?>" alt="Bìa sách" style="width: 50px; height: 70px; object-fit: cover;">
                        </td>
                        
                        <td><div class="fw-bold text-dark"><?= htmlspecialchars($b['title']) ?></div></td>
                        <td class="text-muted small"><?= htmlspecialchars($b['author']) ?></td>
                        <td class="fw-semibold text-primary"><?= number_format($b['price']) ?> ₫</td>
                        <td class="text-center">
                            <?php $stock = (int)($b['stock_quantity'] ?? 0); ?>
                            <span class="badge rounded-pill <?= $stock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> px-2 py-1">
                                <?= $stock ?> cuốn
                            </span>
                        </td>
                        
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="index.php?page=book-edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary rounded-2 px-2.5">
                                    <i class="fa-solid fa-pen-to-square me-1"></i>Sửa
                                </a>
                                <a href="modules/book/delete.php?id=<?= $b['id'] ?>" 
                                    class="btn btn-sm btn-outline-danger rounded-2 px-2.5"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa cuốn sách này không? Hành động này không thể hoàn tác!');">
                                    <i class="fa-solid fa-trash me-1"></i>Xóa
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

