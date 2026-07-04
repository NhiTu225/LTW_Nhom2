<?php
session_start();

// 1. Kết nối cơ sở dữ liệu (Khớp với cấu trúc thư mục của dự án)
if (file_exists('../../config/db.php')) {
    require_once __DIR__ .'/../config/db.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận và dọn dẹp khoảng trắng dữ liệu từ form gửi lên
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 2. Kiểm tra dữ liệu rỗng
    if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Vui lòng điền đầy đủ tất cả các trường thông tin!";
        header("Location: register.php");
        exit();
    }

    if (isset($pdo)) {
        try {
            // 3. Kiểm tra xem tên đăng nhập hoặc Email đã có ai sử dụng chưa
            $check_stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check_stmt->execute([$username, $email]);
            
            if ($check_stmt->fetch()) {
                $_SESSION['error'] = "Tên đăng nhập hoặc địa chỉ Email đã được sử dụng!";
                header("Location: register.php");
                exit();
            }

            // 4. Mã hóa mật khẩu bảo mật (Lưu ý: Nếu đồ án yêu cầu lưu text thô thì dùng thẳng $password, 
            // nhưng khuyên dùng mật khẩu thô để đồng bộ với cơ chế login hiện tại của em)
            $plain_password = $password; 

            // 5. Tiến hành chèn tài khoản mới vào Database (Mặc định phân quyền role là 'user')
            $insert_stmt = $pdo->prepare("INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, 'user')");
            $result = $insert_stmt->execute([$fullname, $username, $email, $plain_password]);

            if ($result) {
                // 6. Lấy ID của tài khoản vừa tạo thành công
                $new_user_id = $pdo->lastInsertId();

                // 7. TỰ ĐỘNG ĐĂNG NHẬP: Bỏ dữ liệu vào túi $_SESSION['user'] khớp 100% với Header
                $_SESSION['user'] = [
                    'id'       => $new_user_id,
                    'username' => $username,
                    'fullname' => $fullname,
                    'email'    => $email,
                    'role'     => 'user'
                ];

                // Thông báo thành công (nếu cần hiển thị ở trang chủ)
                $_SESSION['success'] = "Đăng ký thành viên thành công!";
                
                // Đá người dùng bay thẳng về trang chủ luôn với trạng thái đã đăng nhập
                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['error'] = "Đăng ký thất bại, có lỗi xảy ra trong quá trình xử lý!";
                header("Location: register.php");
                exit();
            }

        } catch (Exception $e) {
            die("Lỗi hệ thống đăng ký: " . $e->getMessage());
        }
    }
} else {
    header("Location: register.php");
    exit();
}