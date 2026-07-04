<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../layouts/header.php';

// Kiểm tra xem giỏ hàng có trống không
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "<div class='container my-5 text-center'><h3>Giỏ hàng của bạn đang trống!</h3><a href='index.php' class='btn btn-danger mt-3'>Tiếp tục mua sắm</a></div>";
    include_once 'layouts/footer.php';
    exit;
}

// Tính tổng tiền đơn hàng
$total_money = 0;
foreach ($cart as $item) {
    $total_money += $item['price'] * $item['quantity'];
}
?>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-5 order-lg-2">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="bx bx-shopping-bag text-danger me-2"></i>Tóm tắt đơn hàng</h5>
                <div class="d-flex flex-column gap-3 mb-3" style="max-height: 250px; overflow-y: auto;">
                    <?php foreach ($cart as $item): ?>
                        <div class="d-flex align-items-center gap-3 border-bottom pb-2">
                            <img src="../public/images/<?= $item['image'] ?>" class="rounded" style="width: 50px; height: 60px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="m-0 small fw-bold text-dark text-truncate" style="max-width: 200px;"><?= $item['title'] ?></h6>
                                <small class="text-muted">SL: <?= $item['quantity'] ?></small>
                            </div>
                            <span class="small fw-bold text-secondary"><?= number_format($item['price'] * $item['quantity']) ?> đ</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="fw-bold text-dark">Tổng cộng:</span>
                    <span class="text-danger fw-bold fs-5"><?= number_format($total_money) ?> đ</span>
                </div>
            </div>
        </div>

        <div class="col-lg-7 order-lg-1">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white">
                
                <div id="step-info">
                    <h5 class="fw-bold text-dark mb-4"><i class="bx bx-user-pin text-danger me-2"></i>Thông tin giao hàng</h5>
                    <form id="form-shipping">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" id="fullname" class="form-control rounded-2" value="<?= $_SESSION['user']['fullname'] ?? '' ?>" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" id="phone" class="form-control rounded-2" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" id="email" class="form-control rounded-2" value="<?= $_SESSION['user']['email'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                            <textarea id="address" class="form-control rounded-2" rows="3" placeholder="Ghi rõ số nhà, tên đường, phường/xã, quận/huyện..." required></textarea>
                        </div>
                        <button type="button" onclick="goToPaymentStep()" class="btn btn-danger w-100 py-2.5 fw-bold rounded-2" style="background-color: #cd1818;">
                            Tiếp tục đến phương thức thanh toán <i class="bx bx-right-arrow-alt align-middle ms-1"></i>
                        </button>
                    </form>
                </div>

                <div id="step-payment" class="d-none text-center animate__animated animate__fadeIn">
                    <h5 class="fw-bold text-dark mb-3 text-start"><i class="bx bx-qr-scan text-danger me-2"></i>Quét mã QR toán</h5>
                    <p class="text-muted small text-start mb-4">Vui lòng sử dụng ứng dụng Ngân hàng hoặc Ví điện tử quét mã QR dưới đây để thanh toán số tiền đơn hàng.</p>
                    
                    <div class="bg-light p-3 rounded-3 d-inline-block border mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=STK:123456789-NGANHANG:MB-TIEN:<?= $total_money ?>" 
                             alt="QR Code Thanh Toán" class="img-fluid rounded" style="max-width: 220px;">
                        <div class="mt-2 small fw-bold text-secondary">Số tiền: <span class="text-danger"><?= number_format($total_money) ?> đ</span></div>
                    </div>

                    <div class="alert alert-warning small rounded-2 text-start mb-4">
                        <i class="bx bx-info-circle align-middle me-1"></i> Sau khi quét mã chuyển khoản xong, bạn vui lòng nhấn nút <strong>"Xác nhận đặt hàng"</strong> phía dưới để hoàn tất.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" onclick="backToInfoStep()" class="btn btn-outline-secondary rounded-2 px-3"><i class="bx bx-left-arrow-alt"></i> Quay lại</button>
                        <button type="button" onclick="submitOrder()" class="btn btn-success flex-grow-1 py-2.5 fw-bold rounded-2">
                            <i class="bx bx-check-circle me-1"></i> Xác nhận đặt hàng thành công
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// Hàm chuyển từ điền thông tin sang quét mã QR
function goToPaymentStep() {
    const name = document.getElementById('fullname').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();

    if(name === '' || phone === '' || address === '') {
        alert('Vui lòng điền đầy đủ các thông tin bắt buộc có dấu (*)!');
        return;
    }

    document.getElementById('step-info').classList.add('d-none');
    document.getElementById('step-payment').classList.remove('d-none');
}

// Hàm quay lại bước điền thông tin
function backToInfoStep() {
    document.getElementById('step-payment').classList.add('d-none');
    document.getElementById('step-info').classList.remove('d-none');
}

// Hàm bấm Xác nhận đặt hàng
function submitOrder() {
    // Ở đây em có thể bắn AJAX lưu DB, tạm thời làm rỗng giỏ hàng và báo thành công
    alert('Đặt hàng thành công! Cảm ơn bạn đã mua sắm tại GroupTwo.');
    
    // Xóa giỏ hàng ngầm rồi chuyển về trang chủ
    window.location.href = 'api/cart-action.php?action=clear_success'; 
}
</script>

<?php include_once 'layouts/footer.php'; ?>