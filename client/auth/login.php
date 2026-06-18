<?php
session_start();

// 1. Kiểm tra nếu người dùng ĐÃ ĐĂNG NHẬP RỒI thì tự động điều hướng đi, không cho ở lại trang login nữa
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../../admin/index.php");
        exit();
    } else {
        header("Location: ../index.php");
        exit();
    }
}

// 2. Lấy thông tin báo lỗi từ Session nếu có (do login_action.php bắn về)
$error_msg = '';
if (isset($_SESSION['error'])) {
    $error_msg = $_SESSION['error'];
    unset($_SESSION['error']); // In ra xong thì xóa ngay để lần sau F5 không bị dính lại
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>

<h2>Đăng nhập</h2>

<?php if (!empty($error_msg)): ?>
    <p style="color:red; font-weight: bold;">
        <?php echo $error_msg; ?>
    </p>
<?php endif; ?>

<form action="login_action.php" method="POST">
    <div>
        <label>Tên đăng nhập (hoặc Email)</label><br>
        <input type="text" name="username" required>
    </div>

    <br>

    <div>
        <label>Mật khẩu</label><br>
        <input type="password" name="password" required>
    </div>

    <br>

    <button type="submit">Đăng nhập</button>
</form>

<p>
    Chưa có tài khoản? 
    <a href="register.php">Đăng ký</a>
</p>

</body>
</html>