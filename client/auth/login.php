<?php
session_start();
// Nếu đã đăng nhập --> về trang chủ, không cho vào lại trang login
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
    <title>Đăng Nhập - GroupTwo BookStore</title>
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
            max-width: 420px;
            width: 100%;
            background: #ffffff;
        }
        .btn-auth {
            background: linear-gradient(135deg, #0072ff 0%, #00c6ff 100%);
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
            box-shadow: 0 0 0 3px rgba(0, 114, 255, 0.15);
            border-color: #0072ff;
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
            <h3 class="fw-bold text-dark mb-1">Chào Mừng Trở Lại</h3>
            <p class="text-secondary small">Vui lòng đăng nhập tài khoản của bạn</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show small p-2" role="alert">
                <i class='bx bx-error-circle align-middle me-1fs-5'></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close p-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="../api/login_action.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-user fs-5'></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập..." required autocomplete="username">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-lock-alt fs-5'></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required autocomplete="current-password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class='bx bx-hide fs-5 align-middle'></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label text-secondary" for="rememberMe">Ghi nhớ</label>
                </div>
                <a href="#" class="text-decoration-none text-primary fw-medium">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn btn-auth w-100 mb-3">ĐĂNG NHẬP</button>

            <div class="text-center small text-secondary">
                Bạn chưa có tài khoản? <a href="register.php" class="text-decoration-none text-primary fw-bold">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Đổi icon mắt
        const icon = this.querySelector('i');
        icon.classList.toggle('bx-hide');
        icon.classList.toggle('bx-show');
    });
</script>
</body>
</html>