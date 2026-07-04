<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản - GroupTwo BookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .auth-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            max-width: 460px;
            width: 100%;
            background: #ffffff;
        }
        .btn-auth {
            background: linear-gradient(135deg, #28a745 0%, #2ecc71 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-auth:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
            border-color: #28a745;
        }
        .input-group-text {
            background-color: #f8f9fa;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="card auth-card p-4 m-3">
    <div class="card-body">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">Tạo Tài Khoản</h3>
            <p class="text-secondary small">Đăng ký thành viên để nhận nhiều ưu đãi mua sách</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show small p-2" role="alert">
                <i class='bx bx-error-circle align-middle me-1 fs-5'></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close p-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="../api/register_action.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Họ và tên</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-id-card fs-5'></i></span>
                    <input type="text" name="fullname" class="form-control" placeholder="Nhập họ và tên..." required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-user fs-5'></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Tạo tên đăng nhập..." required autocomplete="username">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Địa chỉ Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-envelope fs-5'></i></span>
                    <input type="email" name="email" class="form-control" placeholder="ví_dụ@gmail.com" required autocomplete="email">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-lock-alt fs-5'></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Tạo mật khẩu an toàn..." required autocomplete="new-password">
                </div>
            </div>

            <div class="form-check mb-4 small">
                <input class="form-check-input" type="checkbox" id="terms" required checked>
                <label class="form-check-label text-secondary" for="terms">
                    Tôi đồng ý với <a href="#" class="text-decoration-none text-success fw-medium">Điều khoản sử dụng</a> dịch vụ.
                </label>
            </div>

            <button type="submit" class="btn btn-auth w-100 mb-3">ĐĂNG KÝ NGAY</button>

            <div class="text-center small text-secondary">
                Bạn đã có tài khoản rồi? <a href="login.php" class="text-decoration-none text-success fw-bold">Đăng nhập tại đây</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>