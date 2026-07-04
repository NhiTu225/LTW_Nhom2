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

// 🌟 BỔ SUNG: Truy vấn lấy danh sách đánh giá của cuốn sách này từ bảng reviews
$reviews = [];
if (isset($pdo)) {
    $stmt_rev = $pdo->prepare("SELECT r.*, u.fullname FROM reviews r 
                               JOIN users u ON r.user_id = u.id 
                               WHERE r.book_id = ? 
                               ORDER BY r.created_at DESC");
    $stmt_rev->execute([$book['id']]);
    $reviews = $stmt_rev->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container my-5">
    <div class="row bg-white p-4 rounded-3 shadow-sm g-4">
        <div class="col-md-5 text-center border-end">
            <?php 
                // Kiểm tra xem link ảnh có phải là URL trên mạng không (có chứa http)
                $img_src = (strpos($book['image'], 'http') === 0) ? $book['image'] : '../public/images/' . $book['image'];
                ?>
                <img src="<?php echo $img_src; ?>" class="img-fluid rounded shadow-sm mb-3" style="max-height: 400px; object-fit: contain;" onerror="this.src='https://placehold.co/300x400?text=GroupTwo'">
        </div>

        <div class="col-md-7">
            <h2 class="fw-bold mb-2 fs-4 text-dark"><?php echo $book['title']; ?></h2>
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="text-muted small">Mã sản phẩm: <strong>FHS_<?php echo $book['id']; ?></strong></span>
                <?php if (!empty($book['sale'])): ?>
                    <span class="badge bg-danger">Giảm <?= $book['sale'] ?>%</span>
                <?php endif; ?>
            </div>

            <div class="p-3 rounded-3 mb-4" style="background-color: #fafafa;">
                <h3 class="text-danger fw-bold mb-1">
                    <?= number_format($book['price'] ?? 0) ?> đ
                </h3>

                <?php if (isset($book['sale']) && $book['sale'] > 0): ?>
                    <div class="d-flex align-items-center gap-2 small text-secondary">
                        <span class="text-decoration-line-through">
                            <?= number_format($book['old_price'] ?? 0) ?> đ
                        </span>
                        <span class="badge bg-danger">
                            -<?= $book['sale'] ?>%
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <form action="api/checkout-action.php?action=buy&id=<?= $book['id'] ?>" method="POST" class="mb-4">
                <input type="hidden" id="book_id" name="book_id" value="<?php echo $book['id']; ?>">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="fw-medium text-secondary">Số lượng:</span>
                    <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="10" style="width: 80px; border-radius: 6px;">
                </div>

                <div class="row g-2">
                    <div class="col-sm-6">
                        <button type="button" id="btn-add-to-cart" class="btn btn-outline-danger w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-color: #cd1818; color: #cd1818;">
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

    <div class="row bg-white p-4 rounded-3 shadow-sm mt-4">
        <div class="col-12">
            <h4 class="fw-bold text-dark mb-3 fs-5">
                <i class="bx bx-book-open text-danger me-2"></i>Giới thiệu sản phẩm
            </h4>
            <div class="text-secondary small lh-base px-1" style="text-align: justify;">
                <?php 
                if (!empty($book['description'])) {
                    echo nl2br(htmlspecialchars($book['description'])); 
                } else {
                    echo '<span class="text-muted fst-italic">Nội dung giới thiệu chi tiết cho cuốn sách này đang được cập nhật...</span>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row bg-white p-4 rounded-3 shadow-sm g-4 mt-4">
        <div class="col-12">
            <h4 class="fw-bold text-dark mb-4"><i class="bx bx-comment-detail text-danger me-2"></i>Khách hàng nhận xét</h4>
        </div>
        
        <div class="col-lg-7">
            <?php if (empty($reviews)): ?>
                <div class="p-4 bg-light text-center rounded-3 border text-muted small">
                    <i class="bx bx-star d-block mb-2 fs-3 text-secondary"></i>
                    Sản phẩm chưa có đánh giá nào. Hãy là người đầu tiên chia sẻ cảm nhận!
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                    <?php foreach ($reviews as $rev): ?>
                        <div class="p-3 bg-light border rounded-3 shadow-sm">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold text-dark small"><i class="bx bxs-user-circle me-1 text-secondary fs-6 align-middle"></i><?= htmlspecialchars($rev['fullname']) ?></span>
                                <small class="text-muted" style="font-size: 0.75rem;"><?= date('d/m/Y H:i', strtotime($rev['created_at'])) ?></small>
                            </div>
                            <div class="text-warning mb-2" style="font-size: 0.85rem;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bx <?= $i <= $rev['rating'] ? 'bxs-star' : 'bx-star' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="m-0 text-secondary small" style="line-height: 1.5;"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-5">
            <div class="p-4 border rounded-3 bg-light shadow-sm">
                <h5 class="fw-bold text-dark mb-3 fs-6">Viết nhận xét của bạn</h5>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <form action="../api/review-action.php" method="POST">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Đánh giá sản phẩm:</label>
                            <select class="form-select form-select-sm rounded-2" name="rating" required>
                                <option value="5">⭐⭐⭐⭐⭐ 5 Sao - Rất hài lòng</option>
                                <option value="4">⭐⭐⭐⭐ 4 Sao - Tốt</option>
                                <option value="3">⭐⭐⭐ 3 Sao - Bình thường</option>
                                <option value="2">⭐⭐ 2 Sao - Tệ</option>
                                <option value="1">⭐ 1 Sao - Quá kém</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label small fw-semibold text-dark">Nội dung bình luận:</label>
                            <textarea class="form-control rounded-2 small" id="comment" name="comment" rows="4" placeholder="Nhập nhận xét của bạn về nội dung sách, chất lượng in ấn..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-sm btn-danger w-100 rounded-2 fw-bold py-2" style="background-color: #cd1818;">
                            <i class="bx bx-paper-plane me-1"></i> Gửi đánh giá ngay
                        </button>
                    </form>
                <?php else: ?>
                    <div class="text-center py-3">
                        <p class="text-muted small mb-2">Bạn cần đăng nhập tài khoản khách hàng để thực hiện đánh giá.</p>
                        <a href="index.php?page=login" class="btn btn-sm btn-outline-danger rounded-2 px-3">Đăng nhập ngay</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-add-to-cart').addEventListener('click', function(e) {
    const btn = this;
    const bookId = document.getElementById('book_id').value;
    const quantity = document.getElementById('quantity').value;
    const cartIcon = document.getElementById('cart-icon');
    const cartCount = document.getElementById('cart-count');

    if (!cartIcon || !cartCount) {
        console.error("Không tìm thấy các thẻ giỏ hàng ID chuẩn trên Header!");
        return;
    }

    // --- BƯỚC 1: XỬ LÝ QUẢ CẦU BAY ĐỎ ---
    const btnRect = btn.getBoundingClientRect();
    const cartRect = cartIcon.getBoundingClientRect();

    const dot = document.createElement('div');
    dot.className = 'flying-dot';
    dot.style.left = (btnRect.left + btnRect.width / 2 - 9) + 'px';
    dot.style.top = (btnRect.top + btnRect.height / 2 - 9) + 'px';
    document.body.appendChild(dot);

    // Kích hoạt animation di chuyển tịnh tiến
    setTimeout(() => {
        dot.style.left = (cartRect.left + cartRect.width / 2 - 9) + 'px';
        dot.style.top = (cartRect.top + cartRect.height / 2 - 9) + 'px';
        dot.style.transform = 'scale(0.3)';
        dot.style.opacity = '0.4';
    }, 40);

    // --- BƯỚC 2: SAU KHI BAY XONG (0.8s) -> NẢY BADGE & GỬI AJAX ---
    setTimeout(() => {
        dot.remove();
        
        cartCount.classList.add('bump');
        setTimeout(() => cartCount.classList.remove('bump'), 250);

        // Gửi dữ liệu bằng API XMLHttpRequest (AJAX thuần không cần thư viện)
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/cart-action.php?action=add&id=' + bookId, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                const responseText = xhr.responseText.trim();
                // Nếu Backend trả về tổng số mặt hàng (là con số 2 như em thấy), gán trực tiếp vào Badge
                if(!isNaN(responseText) && responseText !== '') {
                    cartCount.innerText = responseText;
                } else {
                    // Dự phòng nếu lỗi chuỗi, ta tự tăng số lượng trên giao diện
                    cartCount.innerText = parseInt(cartCount.innerText) + parseInt(quantity);
                }
            }
        };
        xhr.send('quantity=' + quantity);

    }, 800);
});
</script>

<?php include_once 'layouts/footer.php'; ?>