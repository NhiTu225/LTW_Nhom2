<?php
if (isset($pdo)) {
    // Lấy toàn bộ danh sách tài khoản ngoại trừ tài khoản admin tối cao
    $users = $pdo->query("SELECT id, username, fullname, email, created_at FROM users WHERE role = 'user' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="card panel-box p-3 shadow-sm border-0">
    <h6 class="fw-bold mb-3"><i class="fa-solid fa-users me-2"></i>Danh sách tài khoản khách hàng</h6>
    <div class="table-responsive">
        <table class="table align-middle m-0 small table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Ngày đăng ký</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($users)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">Chưa có khách hàng nào đăng ký.</td></tr>
                <?php else: ?>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['fullname'] ?? 'Chưa cập nhật') ?></td>
                        <td><?= htmlspecialchars($u['email'] ?? 'Chưa cập nhật') ?></td>
                        <td class="text-muted"><?= $u['created_at'] ?></td>
                        <td>
                            <a href="modules/user/delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản khách hàng này?')"><i class="fa-solid fa-trash"></i> Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>