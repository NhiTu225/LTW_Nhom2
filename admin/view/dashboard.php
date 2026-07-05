<?php
// Khởi tạo mảng số liệu an toàn đề phòng DB trống
$thong_ke = ['doanh_thu' => 0, 'don_hang' => 0, 'sach' => 0, 'khach_hang' => 0];
$trang_thai_don = ['pending' => 0, 'processing' => 0, 'shipping' => 0, 'completed' => 0, 'cancelled' => 0, 'canceled' => 0];
$don_hang_moi = [];
$top_sach = [];

// Khởi tạo mảng doanh thu 12 tháng mặc định bằng 0 cho biểu đồ
$data_bieu_do = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; 

if (isset($pdo)) {
    try {
        $completed_status_sql = "LOWER(TRIM(status)) IN ('completed','received','đã hoàn thành','đã nhận hàng','da hoan thanh','da nhan hang')";

        // 🌟 1. Doanh thu dashboard tính theo các đơn đã hoàn thành/đã nhận hàng để thống nhất với Top sách bán chạy
        $thong_ke['doanh_thu'] = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE " . $completed_status_sql)->fetchColumn() ?? 0;
        $thong_ke['don_hang'] = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn() ?? 0;
        $thong_ke['sach'] = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn() ?? 0;
        $thong_ke['khach_hang'] = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user' OR role = 'client'")->fetchColumn() ?? 0;

        // 2. Đếm số lượng đơn theo từng trạng thái thực tế
        $stmt = $pdo->query("SELECT status, COUNT(*) AS so_luong FROM orders GROUP BY status");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $raw_status = strtolower(trim((string)($row['status'] ?? '')));
            $normalized_status = $raw_status;

            if ($raw_status === 'cancelled' || $raw_status === 'canceled' || $raw_status === 'đã hủy' || $raw_status === 'da huy' || $raw_status === 'huy') {
                $normalized_status = 'cancelled';
            } elseif (in_array($raw_status, ['pending', 'processing', 'shipping', 'completed'], true)) {
                $normalized_status = $raw_status;
            }

            if (array_key_exists($normalized_status, $trang_thai_don)) {
                $trang_thai_don[$normalized_status] = (int)$row['so_luong'];
                if ($normalized_status === 'cancelled') {
                    $trang_thai_don['canceled'] = (int)$row['so_luong'];
                }
            }
        }

        // 3. o.total_price thành o.total_amount và lấy fullname từ bảng users
        $don_hang_moi = $pdo->query("SELECT o.*, u.fullname AS customer_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        // 4. Doanh thu Top sách bán chạy cũng chỉ tính theo các đơn đã hoàn thành/đã nhận hàng
        $top_sach = $pdo->query("SELECT b.title, b.author, SUM(od.quantity) as da_ban, COALESCE(SUM(od.quantity * od.price), 0) as doanh_thu FROM order_items od JOIN books b ON od.book_id = b.id JOIN orders o ON o.id = od.order_id WHERE " . $completed_status_sql . " GROUP BY od.book_id ORDER BY da_ban DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        // 5. Biểu đồ doanh thu cũng dùng cùng tiêu chí trạng thái đã hoàn thành/đã nhận hàng
        $sql_chart = "SELECT MONTH(created_at) AS thang, SUM(total_amount) AS tong FROM orders WHERE " . $completed_status_sql . " GROUP BY MONTH(created_at)";
        $stmt_chart = $pdo->query($sql_chart);
        while ($row_chart = $stmt_chart->fetch(PDO::FETCH_ASSOC)) {
            $m = (int)$row_chart['thang'];
            if ($m >= 1 && $m <= 12) {
                $data_bieu_do[$m - 1] = (int)$row_chart['tong'];
            }
        }

    } catch (Exception $e) {
        echo "<div class='alert alert-danger small'>Lỗi truy vấn Dashboard: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card panel-box p-3 d-flex flex-row align-items-center justify-content-between shadow-sm border-0">
            <div>
                <small class="text-muted d-block mb-1">Tổng doanh thu</small>
                <h4 class="fw-bold m-0 text-dark"><?= number_format($thong_ke['doanh_thu']) ?> đ</h4>
            </div>
            <div class="bg-success-subtle text-success rounded-3 p-3 fs-4"><i class="fa-solid fa-wallet"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card panel-box p-3 d-flex flex-row align-items-center justify-content-between shadow-sm border-0">
            <div>
                <small class="text-muted d-block mb-1">Số đơn hàng</small>
                <h4 class="fw-bold m-0 text-dark"><?= $thong_ke['don_hang'] ?></h4>
            </div>
            <div class="bg-primary-subtle text-primary rounded-3 p-3 fs-4"><i class="fa-solid fa-box"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card panel-box p-3 d-flex flex-row align-items-center justify-content-between shadow-sm border-0">
            <div>
                <small class="text-muted d-block mb-1">Số sách đang bán</small>
                <h4 class="fw-bold m-0 text-dark"><?= $thong_ke['sach'] ?></h4>
            </div>
            <div class="bg-warning-subtle text-warning rounded-3 p-3 fs-4"><i class="fa-solid fa-book"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card panel-box p-3 d-flex flex-row align-items-center justify-content-between shadow-sm border-0">
            <div>
                <small class="text-muted d-block mb-1">Số khách hàng</small>
                <h4 class="fw-bold m-0 text-dark"><?= $thong_ke['khach_hang'] ?></h4>
            </div>
            <div class="bg-info-subtle text-info rounded-3 p-3 fs-4"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card panel-box h-100 p-3 shadow-sm border-0">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-chart-line me-2"></i>Biểu đồ doanh thu tháng</h6>
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card panel-box h-100 p-3 shadow-sm border-0">
            <h6 class="fw-bold text-dark mb-3">Tình trạng xử lý đơn</h6>
            <div class="list-group list-group-flush small">
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                    <span><i class="fa-solid fa-circle text-warning me-2" style="font-size:0.6rem;"></i>Chờ xác nhận</span>
                    <span class="badge bg-warning-subtle text-warning rounded-pill"><?= $trang_thai_don['pending'] ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                    <span><i class="fa-solid fa-circle text-primary me-2" style="font-size:0.6rem;"></i>Đang xử lý</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill"><?= $trang_thai_don['processing'] ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                    <span><i class="fa-solid fa-circle text-info me-2" style="font-size:0.6rem;"></i>Đang giao</span>
                    <span class="badge bg-info-subtle text-info rounded-pill"><?= $trang_thai_don['shipping'] ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                    <span><i class="fa-solid fa-circle text-success me-2" style="font-size:0.6rem;"></i>Đã hoàn thành</span>
                    <span class="badge bg-success-subtle text-success rounded-pill"><?= $trang_thai_don['completed'] ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                    <span><i class="fa-solid fa-circle text-danger me-2" style="font-size:0.6rem;"></i>Đã hủy</span>
                    <span class="badge bg-danger-subtle text-danger rounded-pill"><?= $trang_thai_don['cancelled'] ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card panel-box p-3 shadow-sm border-0">
            <h6 class="fw-bold mb-3">Đơn hàng mới nhất</h6>
            <div class="table-responsive">
                <table class="table align-middle m-0 small table-hover">
                    <thead class="table-light">
                        <tr><th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Trạng thái</th></tr>
                    </thead>
                    <tbody>
                        <?php if(empty($don_hang_moi)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">Chưa có dữ liệu đơn hàng.</td></tr>
                        <?php else: ?>
                            <?php foreach($don_hang_moi as $dh): ?>
                            <tr>
                                <td class="fw-bold">#<?= $dh['id'] ?></td>
                                <td><?= htmlspecialchars($dh['customer_name'] ?? 'Khách vãng lai') ?></td>
                                <td class="text-primary fw-semibold"><?= number_format($dh['total_amount'] ?? 0) ?> đ</td>
                                <td><span class="badge bg-secondary"><?= $dh['status'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card panel-box p-3 shadow-sm border-0">
            <h6 class="fw-bold mb-3">Top sách bán chạy</h6>
            <div class="table-responsive">
                <table class="table align-middle m-0 small table-hover">
                    <thead class="table-light">
                        <tr><th>Tên sách</th><th>Tác giả</th><th>Đã bán</th><th>Doanh thu</th></tr>
                    </thead>
                    <tbody>
                        <?php if(empty($top_sach)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">Chưa có dữ liệu bán hàng.</td></tr>
                        <?php else: ?>
                            <?php foreach($top_sach as $ts): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($ts['title']) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($ts['author']) ?></td>
                                <td class="text-center fw-bold"><?= $ts['da_ban'] ?></td>
                                <td class="text-success fw-semibold"><?= number_format($ts['doanh_thu']) ?> đ</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line', // Giữ nguyên dạng đường mượt mà hoặc đổi thành 'bar' nếu thích cột
        data: {
            // Đổi nhãn hiển thị thành 12 tháng trong năm
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [{
                label: 'Doanh thu năm nay (đ)',
                // 🌟 ĐÃ CẬP NHẬT: Đổ mảng dữ liệu 12 tháng động từ PHP vào đây
                data: <?= json_encode($data_bieu_do) ?>, 
                borderColor: '#2563eb', // Đổi màu xanh đậm hơn nhìn cho chuyên nghiệp
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#2563eb',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
