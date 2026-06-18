<?php
if (isset($pdo)) {
    // Truy vấn danh sách đơn hàng, kết nối với bảng users để lấy tên khách hàng
    // Bạn lưu ý kiểm tra lại tên các cột trong bảng orders của bạn (ví dụ: total_amount hay total_price)
    $sql = "SELECT o.*, u.fullname FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.id DESC";
    $orders = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="card panel-box p-3 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold m-0"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Quản lý danh sách đơn hàng</h6>
    </div>
    
    <div class="table-responsive">
        <table class="table align-middle m-0 small table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">Chưa có đơn hàng nào được đặt.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td class="fw-bold">#<?= $o['id'] ?></td>
                        <td><?= htmlspecialchars($o['fullname'] ?? 'Khách vãng lai') ?></td>
                        <td class="text-muted"><?= $o['created_at'] ?></td>
                        <td class="fw-bold text-danger"><?= number_format($o['total_amount'] ?? $o['total_price'] ?? 0) ?> đ</td>
                        <td>
                            <?php 
                            // Đổ màu trạng thái đồng bộ với Dashboard của bạn
                            $status = $o['status'] ?? 'pending';
                            if ($status == 'pending' || $status == 'Chờ xác nhận') {
                                echo '<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1">Chờ xác nhận</span>';
                            } elseif ($status == 'processing' || $status == 'Đang xử lý') {
                                echo '<span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1">Đang xử lý</span>';
                            } elseif ($status == 'shipping' || $status == 'Đang giao') {
                                echo '<span class="badge bg-info-subtle text-info border border-info px-2 py-1">Đang giao</span>';
                            } elseif ($status == 'completed' || $status == 'Đã hoàn thành') {
                                echo '<span class="badge bg-success-subtle text-success border border-success px-2 py-1">Đã hoàn thành</span>';
                            } else {
                                echo '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">Đã hủy</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <form action="modules/order/update.php" method="POST" class="d-inline-flex gap-1 align-items-center">
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <select name="status" class="form-select form-select-sm" style="width: 140px; font-size: 0.75rem;">
                                    <option value="Chờ xác nhận" <?= ($o['status']=='Chờ xác nhận' || $o['status']=='pending')?'selected':'' ?>>Chờ xác nhận</option>
                                    <option value="Đang xử lý" <?= ($o['status']=='Đang xử lý' || $o['status']=='processing')?'selected':'' ?>>Đang xử lý</option>
                                    <option value="Đang giao" <?= ($o['status']=='Đang giao' || $o['status']=='shipping')?'selected':'' ?>>Đang giao</option>
                                    <option value="Đã hoàn thành" <?= ($o['status']=='Đã hoàn thành' || $o['status']=='completed')?'selected':'' ?>>Đã hoàn thành</option>
                                    <option value="Đã hủy" <?= ($o['status']=='Đã hủy' || $o['status']=='cancelled')?'selected':'' ?>>Đã hủy</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary py-1 px-2" style="font-size: 0.75rem;">Cập nhật</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>