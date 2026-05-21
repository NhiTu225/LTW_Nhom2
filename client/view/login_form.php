<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>

<h2>Đăng nhập</h2>

<p style="color:red;">
    <?php echo $message; ?>
</p>

<form action="../auth/login.php" method="POST">
    <div>
        <label>Email</label><br>
        <input type="email" name="email">
    </div>

    <br>

    <div>
        <label>Password</label><br>
        <input type="password" name="password">
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