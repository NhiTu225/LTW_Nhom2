<?php
// include_once '../../config/db.php';

$user = $_SESSION['user'];
?>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-3 col-md-4">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fs-3" style="width: 50px; height: 50px;">
                    <i class='bx bx-user'></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark text-truncate" style="max-width: 150px;">
                        <?= htmlspecialchars($user['username']) ?>
                    </h6>
                    <a href="index.php?page=profile" class="text-muted small text-decoration-none">
                        <i class='bx bxs-pencil'></i> Sửa Hồ Sơ
                    </a>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 profile-sidebar">
                <a href="index.php?page=profile" class="nav-link-profile active text-danger fw-semibold">
                    <i class='bx bx-user-circle fs-5 align-middle me-2'></i> Hồ Sơ Của Tôi
                </a>
                <a href="index.php?page=my-orders" class="nav-link-profile text-dark">
                    <i class='bx bx-notepad fs-5 align-middle me-2'></i> Đơn Mua
                </a>
                <a href="#" class="nav-link-profile text-dark">
                    <i class='bx bx-bell fs-5 align-middle me-2'></i> Thông Báo
                </a>
                <a href="#" class="nav-link-profile text-dark">
                    <i class='bx bx-purchase-tag fs-5 align-middle me-2'></i> Kho Voucher
                </a>
                <hr class="my-2">
                <a href="auth/logout.php" class="nav-link-profile text-secondary hover-danger-text">
                    <i class='bx bx-log-out fs-5 align-middle me-2 text-danger'></i> Đăng xuất
                </a>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="card border-0 rounded-3 shadow-sm bg-white p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h5 class="fw-bold text-dark mb-1">Hồ Sơ Của Tôi</h5>
                    <p class="text-muted small mb-0">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                </div>

                <div class="row g-4 flex-column-reverse flex-lg-row">
                    <div class="col-lg-8 border-end-lg">
                        <form action="view/profile-action.php" method="POST">
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-3 col-form-class text-secondary text-sm-end small">Tên đăng nhập</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control-plaintext fw-semibold text-dark px-2" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-3 col-form-class text-secondary text-sm-end small">Họ và tên</label>
                                <div class="col-sm-9">
                                    <input type="text" name="fullname" class="form-control form-control-sm py-2" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" style="border-radius: 6px;">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-3 col-form-class text-secondary text-sm-end small">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control form-control-sm py-2" value="<?= htmlspecialchars($user['email'] ?? '') ?>" style="border-radius: 6px;">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-3 col-form-class text-secondary text-sm-end small">Vai trò hệ thống</label>
                                <div class="col-sm-9">
                                    <span class="badge <?= ($user['role'] === 'admin') ? 'bg-danger' : 'bg-primary' ?> px-2.5 py-1.5 rounded-2 text-uppercase" style="font-size: 0.7rem;">
                                        <?= htmlspecialchars($user['role']) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn text-white btn-sm px-4 py-2 fw-bold" style="background-color: #cd1818; border-radius: 4px;">
                                        Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-4 text-center d-flex flex-column align-items-center justify-content-center py-3">
                        <div class="position-relative mb-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted border border-2 shadow-2xs" style="width: 100px; height: 100px; font-size: 3rem;">
                                <i class='bx bx-user'></i>
                            </div>
                        </div>
                        <!-- <input type="file"> -->
                        <button class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 4px;" disabled>Chọn ảnh</button>
                        <div class="text-muted small mt-2" style="font-size: 0.75rem;">
                            Dụng lượng file tối đa 1 MB<br>Định dạng: .JPEG, .PNG
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* CSS hỗ trợ làm hiệu ứng menu Sidebar đẹp mắt */
    .nav-link-profile {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: block;
    }
    .nav-link-profile:hover:not(.active) {
        background-color: #f8f9fa;
        color: #cd1818 !important;
        padding-left: 16px;
    }
    .nav-link-profile.active {
        background-color: #fff5f5;
    }
    .hover-danger-text:hover {
        color: #dc3545 !important;
    }
    @media (min-width: 992px) {
        .border-end-lg {
            border-end: 1px solid #dee2e6 !important;
        }
    }
</style>