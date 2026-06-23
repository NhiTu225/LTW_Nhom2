<?php
include_once __DIR__ . '/../layouts/header.php';
include_once __DIR__ . '/../../config/db.php';

// Lấy ID từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query thông tin sách
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute([':id' => $id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "<div class='container my-5 text-center'><h3>Sản phẩm không tồn tại hoặc đã bị xóa!</h3><a href='index.php' class='btn btn-danger mt-3'>Quay lại trang chủ</a></div>";
    include_once 'layouts/footer.php';
    exit;
}
?>

<div class="container my-5">
    <!-- <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page"><?php echo $book['category']; ?></li>
        </ol>
    </nav> -->

    <div class="row bg-white p-4 rounded-3 shadow-sm g-4">
        <div class="col-md-5 text-center border-end">
            <img src="../public/images/<?php echo $book['image']; ?>" class="img-fluid rounded shadow-sm mb-3" style="max-height: 400px; object-fit: contain;" onerror="this.src='https://placehold.co/300x400?text=Fahasa'">
        </div>

        <div class="col-md-7">
            <h2 class="fw-bold mb-2 fs-4 text-dark"><?php echo $book['title']; ?></h2>
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="text-muted small">Mã sản phẩm: <strong>FHS_<?php echo $book['id']; ?></strong></span>
                <span class="badge bg-danger">-<?php echo $book['sale']; ?></span>
            </div>

            <div class="p-3 rounded-3 mb-4" style="background-color: #fafafa;">
                <h3 class="text-danger fw-bold mb-1 fs-3"><?php echo number_format($book['price']); ?> đ</h3>
                <span class="text-muted text-decoration-line-through small"><?php echo number_format($book['old_price']); ?> đ</span>
            </div>

            <form action="view/cart-action.php?action=add&id=<?= $book['id'] ?>" method="POST" class="mb-4">
                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="fw-medium text-secondary">Số lượng:</span>
                    <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="10" style="width: 80px; border-radius: 6px;">
                </div>

                <div class="row g-2">
                    <div class="col-sm-6">
                        <button type="submit" name="add_to_cart" class="btn btn-outline-danger w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-color: #cd1818; color: #cd1818;">
                            <i class='bx bx-cart-add fs-5'></i> Thêm vào giỏ hàng
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" name="buy_now" class="btn text-white w-100 py-2.5 fw-bold" style="background-color: #cd1818;">
                            Mua ngay
                        </button>
                    </div>
                </div>
            </form>

            <hr>
            <div class="row row-cols-1 row-cols-sm-2 g-2 pt-2 text-muted small">
                <div class="col"><i class='bx bx-refresh text-danger fs-5 align-middle me-1'></i> Chính sách đổi trả nhanh chóng</div>
                <div class="col"><i class='bx bx-package text-danger fs-5 align-middle me-1'></i> Giao hàng siêu tốc toàn quốc</div>
            </div>
        </div>
    </div>
</div>


<?php include_once 'layouts/footer.php'; ?>