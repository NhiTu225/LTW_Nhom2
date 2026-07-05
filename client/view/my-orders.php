<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: auth/login.php');
    exit;
}

include_once __DIR__ . '/../layouts/header.php';
include_once __DIR__ . '/../../config/db.php';

$user_id = intval($_SESSION['user']['id'] ?? 0);

if (!isset($pdo)) {
    echo '<div class="container my-5"><div class="alert alert-danger">Không thể kết nối cơ sở dữ liệu.</div></div>';
    include_once __DIR__ . '/../layouts/footer.php';
    exit;
}

function order_status_badge($status) {
    $status = strtolower($status ?? 'pending');
    $map = [
        'pending' => ['label' => 'Chờ xác nhận', 'class' => 'bg-warning-subtle text-warning border-warning'],
        'processing' => ['label' => 'Đang xử lý', 'class' => 'bg-primary-subtle text-primary border-primary'],
        'shipping' => ['label' => 'Đang giao', 'class' => 'bg-info-subtle text-info border-info'],
        'completed' => ['label' => 'Đã giao', 'class' => 'bg-success-subtle text-success border-success'],
        'received' => ['label' => 'Đã nhận hàng', 'class' => 'bg-dark-subtle text-dark border-dark'],
        'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-danger-subtle text-danger border-danger'],
    ];

    $item = $map[$status] ?? $map['pending'];
    return '<span class="badge border ' . $item['class'] . ' px-2 py-1">' . $item['label'] . '</span>';
}

function timeline_steps($status) {
    $status = strtolower($status ?? 'pending');
    $steps = [
        ['key' => 'pending', 'label' => 'Chờ xác nhận', 'icon' => 'bx bx-wallet'],
        ['key' => 'processing', 'label' => 'Đang xử lý', 'icon' => 'bx bx-package'],
        ['key' => 'shipping', 'label' => 'Đang giao', 'icon' => 'bx bx-car'],
        ['key' => 'completed', 'label' => 'Đã giao', 'icon' => 'bx bx-check-circle'],
        ['key' => 'received', 'label' => 'Đã nhận hàng', 'icon' => 'bx bx-smile'],
    ];

    $index = array_search($status, array_column($steps, 'key'), true);
    if ($index === false) {
        $index = 0;
    }

    $html = '<div class="mb-3">';
    $html .= '<div class="d-flex align-items-center flex-wrap gap-2 mb-2">';
    foreach ($steps as $i => $step) {
        $done = $i <= $index;
        $active = $i === $index;
        $circle_style = $done ? 'background:#dc3545;color:#fff;border-color:#dc3545;' : 'background:#f8f9fa;color:#6c757d;border-color:#dee2e6;';
        $text_cls = $done ? 'text-danger fw-semibold' : 'text-muted';
        $line_bg = $done ? '#dc3545' : '#dee2e6';

        $html .= '<div class="d-flex align-items-center flex-grow-1">';
        $html .= '<div class="rounded-circle border d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; font-size: 0.95rem; ' . $circle_style . '"><i class="' . $step['icon'] . '"></i></div>';
        $html .= '<div class="ms-2 small ' . $text_cls . '">' . $step['label'] . '</div>';
        if ($i < count($steps) - 1) {
            $html .= '<div class="flex-grow-1 mx-2" style="height: 3px; background:' . $line_bg . '; min-width: 18px; border-radius: 999px;"></div>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';
    if ($active && $status !== 'received') {
        $html .= '<div class="small text-danger fw-semibold">Đang ở giai đoạn hiện tại</div>';
    }
    $html .= '</div>';
    return $html;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Đơn hàng của tôi</h3>
            <p class="text-muted mb-0">Theo dõi trạng thái đơn hàng và đánh giá sản phẩm sau khi nhận hàng.</p>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-light border rounded-3 shadow-sm">
            Bạn chưa có đơn hàng nào.
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php
            $items_stmt = $pdo->prepare("SELECT oi.*, b.title, b.image FROM order_items oi LEFT JOIN books b ON b.id = oi.book_id WHERE oi.order_id = ?");
            $items_stmt->execute([$order['id']]);
            $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
            $status = strtolower($order['status'] ?? 'pending');
            ?>
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Đơn hàng #<?= $order['id'] ?></h5>
                            <div class="text-muted small">Ngày đặt: <?= htmlspecialchars($order['created_at'] ?? '') ?></div>
                        </div>
                        <div class="text-md-end">
                            <?= order_status_badge($status) ?>
                            <div class="fw-bold text-danger mt-2">Tổng tiền: <?= number_format($order['total_amount'] ?? 0) ?> đ</div>
                        </div>
                    </div>

                    <?= timeline_steps($status) ?>

                    <?php if ($status === 'pending' && !in_array($order['status'] ?? 'pending', ['processing', 'shipping', 'completed', 'received', 'cancelled'], true)): ?>
                        <form action="api/order_action.php" method="POST" class="mb-3">
                            <input type="hidden" name="action" value="cancel_order">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class='bx bx-x-circle me-1'></i> Hủy đơn
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($status === 'completed'): ?>
                        <form action="api/order_action.php" method="POST" class="mb-3">
                            <input type="hidden" name="action" value="confirm_received">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <i class='bx bx-check-circle me-1'></i> Xác nhận đã nhận hàng
                            </button>
                        </form>
                    <?php elseif ($status === 'received'): ?>
                        <div class="alert alert-success py-2 px-3 small mb-3">Bạn đã xác nhận nhận hàng. Bạn có thể đánh giá sản phẩm bên dưới.</div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <?php foreach ($items as $item): ?>
                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex gap-3">
                                        <?php
                                        $image_name = !empty($item['image']) ? $item['image'] : '2.png';
                                        $item_img = (strpos($image_name, 'http') === 0) ? $image_name : '../public/images/' . $image_name;
                                        ?>
                                        <img src="<?= $item_img ?>" class="rounded" style="width: 70px; height: 90px; object-fit: cover;" onerror="this.src='https://placehold.co/100x140?text=Book'">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($item['title'] ?? 'Sản phẩm') ?></h6>
                                            <div class="small text-muted">Số lượng: <?= intval($item['quantity'] ?? 1) ?></div>
                                            <div class="small text-danger fw-bold">Giá: <?= number_format($item['price'] ?? 0) ?> đ</div>
                                        </div>
                                    </div>

                                    <?php
                                    $reviewed_stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND book_id = ?");
                                    $reviewed_stmt->execute([$user_id, intval($item['book_id'] ?? 0)]);
                                    $already_reviewed = (bool)$reviewed_stmt->fetch();
                                    ?>
                                    <?php if ($status === 'received' && !$already_reviewed): ?>
                                        <form action="api/review_action.php" method="POST" class="mt-3">
                                            <input type="hidden" name="book_id" value="<?= intval($item['book_id'] ?? 0) ?>">
                                            <div class="mb-2">
                                                <label class="form-label small fw-semibold">Đánh giá</label>
                                                <select name="rating" class="form-select form-select-sm" required>
                                                    <option value="5">⭐⭐⭐⭐⭐ Tuyệt vời</option>
                                                    <option value="4">⭐⭐⭐⭐ Tốt</option>
                                                    <option value="3">⭐⭐⭐ Bình thường</option>
                                                    <option value="2">⭐⭐ Kém</option>
                                                    <option value="1">⭐ Rất kém</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-semibold">Nhận xét</label>
                                                <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Bạn thấy sản phẩm thế nào?" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Gửi đánh giá</button>
                                        </form>
                                    <?php elseif ($already_reviewed): ?>
                                        <div class="alert alert-secondary py-2 px-3 small mt-3 mb-0">Bạn đã đánh giá sản phẩm này.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
