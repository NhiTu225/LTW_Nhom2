<?php
// Kiểm tra xem giỏ hàng có sản phẩm nào không
$cart = $_SESSION['cart'] ?? [];
$total_money = 0;
?>

<div class="container my-5">
    <h3 class="fw-bold text-uppercase mb-4 fs-4 text-danger">🛒 Giỏ Hàng Của Bạn</h3>

    <?php if (empty($cart)): ?>
        <!-- Giao diện khi giỏ hàng trống -->
        <div class="text-center py-5 bg-white rounded-3 shadow-sm">
            <div class="mb-3">
                <i class='bx bx-shopping-bag text-muted' style="font-size: 5rem;"></i>
            </div>
            <p class="text-muted fs-5">Giỏ hàng của bạn đang trống. Mua sắm ngay!</p>
            <a href="index.php" class="btn text-white px-4 py-2 mt-2 fw-bold" style="background-color: #cd1818;">
                Quay lại mua sắm
            </a>
        </div>
    <?php else: ?>
        <!-- Giao diện khi có sản phẩm -->
        <div class="row g-4">
            <!-- Cột danh sách sản phẩm -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-3 shadow-sm p-3 bg-white">
                    <!-- Form gửi lên view/cart-action.php?action=update để cập nhật số lượng -->
                    <form action="../api/cart_action.php?action=update" method="POST">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 45%;">Sản phẩm</th>
                                        <th scope="col" class="text-center" style="width: 15%;">Giá</th>
                                        <th scope="col" class="text-center" style="width: 20%;">Số lượng</th>
                                        <th scope="col" class="text-center" style="width: 15%;">Thành tiền</th>
                                        <th scope="col" class="text-center" style="width: 5%;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $id => $item): 
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total_money += $subtotal;
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php
                                                    $cart_item_image = !empty($item['image']) ? $item['image'] : '2.png';
                                                    $cart_item_image_src = (strpos($cart_item_image, 'http') === 0) ? $cart_item_image : '../public/images/' . $cart_item_image;
                                                    ?>
                                                    <img src="<?= $cart_item_image_src ?>" class="img-fluid rounded" style="width: 60px; height: 80px; object-fit: contain;" onerror="this.src='https://placehold.co/150x200?text=Book'">
                                                    <div class="text-truncate-2 small fw-semibold text-dark" style="max-width: 200px;">
                                                        <?= htmlspecialchars($item['title']) ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-danger fw-medium small">
                                                <?= number_format($item['price']) ?> đ
                                            </td>
                                            <td class="text-center">
                                                <!-- Ô nhập số lượng, gài id làm key mảng quantity[id] giống hệt trong file cart-action.php xử lý -->
                                                <input type="number" name="quantity[<?= $id ?>]" class="form-control form-control-sm text-center mx-auto" value="<?= $item['quantity'] ?>" min="1" max="10" style="width: 65px; border-radius: 6px;">
                                            </td>
                                            <td class="text-center text-danger fw-bold small">
                                                <?= number_format($subtotal) ?> đ
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex flex-column gap-2">
                                                    <form action="api/checkout-action.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="buy">
                                                        <input type="hidden" name="book_id" value="<?= intval($id) ?>">
                                                        <input type="hidden" name="quantity" value="<?= intval($item['quantity']) ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">Thanh toán</button>
                                                    </form>
                                                    <a href="api/cart_action.php?action=delete&id=<?= intval($id) ?>" class="btn btn-sm btn-outline-secondary">Xóa
                                                        <i class='bx bx-trash'></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Khu vực nút bấm cập nhật số lượng -->
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <a href="index.php" class="text-decoration-none text-danger small fw-semibold">
                                <i class='bx bx-arrow-back align-middle'></i> Tiếp tục mua sản phẩm khác
                            </a>
                            <button type="submit" class="btn btn-sm btn-outline-dark fw-bold px-3 py-1.5" style="border-radius: 6px;">
                                <i class='bx bx-refresh align-middle fs-6'></i> Cập nhật giỏ hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cột tóm tắt đơn hàng và thanh toán -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-3 shadow-sm p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Tóm tắt đơn hàng</h5>
                    
                    <div class="d-flex justify-content-between mb-2 text-secondary small">
                        <span>Tạm tính:</span>
                        <span><?= number_format($total_money) ?> đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-secondary small">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success fw-medium">Miễn phí</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold text-dark">Tổng tiền thanh toán:</span>
                        <span class="fw-bold text-danger fs-4"><?= number_format($total_money) ?> đ</span>
                    </div>

                    <!-- Nút dẫn tới trang checkout -->
                    <a href="index.php?page=checkout" class="btn text-white w-100 py-2.5 fw-bold text-uppercase shadow-2xs" style="background-color: #cd1818; border-radius: 6px; letter-spacing: 0.5px;">
                        Tiến hành thanh toán <i class='bx bx-chevron-right align-middle fs-5'></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    /* CSS hỗ trợ làm đẹp hiệu ứng hover cho icon xóa */
    .hover-danger:hover {
        color: #dc3545 !important;
        transition: color 0.2s ease-in-out;
    }
</style>