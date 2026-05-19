<?php
$host = 'localhost';
$dbname = 'ltw_nhom2';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Lấy mã lỗi kết nối (Ví dụ: 2002 là sập kết nối, 1049 là sai tên DB, 1045 là sai mật khẩu)
    $errorCode = $e->getCode();
    $errorMessage = $e->getMessage();
    
    // Tự động phân loại loại lỗi để hiển thị trực quan
    if ($errorCode == 2002) {
        $errorType = "LỖI HỆ THỐNG MÔI TRƯỜNG (Có thể chưa bật MySQL trong XAMPP)";
        $advice = "Bạn hãy mở XAMPP Control Panel và bấm nút <strong>Start</strong> bên cạnh dịch vụ MySQL nhé.";
    } elseif ($errorCode == 1049) {
        $errorType = "LỖI KHÔNG TÌM THẤY CƠ SỞ DỮ LIỆU (Database Not Found)";
        $advice = "Hãy chắc chắn rằng bạn đã tạo database có tên chính xác là <strong>'$dbname'</strong> trong phpMyAdmin.";
    } elseif ($errorCode == 1045) {
        $errorType = "LỖI XÁC THỰC TÀI KHOẢN (Access Denied)";
        $advice = "Kiểm tra lại cấu hình <strong>$username</strong> hoặc <strong>$password</strong> trong file config.";
    } else {
        $errorType = "LỖI TRUY VẤN HOẶC KHÔNG TÌM THẤY TÀI NGUYÊN (404/Database Error)";
        $advice = "Hệ thống gặp sự cố khi tải tài nguyên hoặc cấu trúc bảng dữ liệu bị thay đổi.";
    }

    // Xuất ra một giao diện thông báo lỗi đẹp mắt, dễ nhìn thay vì một dòng chữ đen thui sập nguồn
    echo "
    <div style='max-width: 600px; margin: 50px auto; padding: 25px; border-left: 5px solid #cd1818; background-color: #fff5f5; font-family: sans-serif; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
        <h3 style='color: #cd1818; margin-top: 0; display: flex; align-items: center; gap: 10px;'>
            ⚠️ $errorType
        </h3>
        <p style='color: #555; font-size: 0.95rem; line-height: 1.6;'>
            <strong>Chi tiết lỗi:</strong> <code style='background: #eee; padding: 2px 6px; border-radius: 3px; color: #c7254e;'>$errorMessage</code>
        </p>
        <hr style='border: 0; border-top: 1px solid #ffebeb; margin: 15px 0;'>
        <p style='color: #2e7d32; font-size: 0.9rem; margin-bottom: 0;'>
            💡 <strong>Gợi ý khắc phục :</strong> $advice
        </p>
    </div>
    ";
    
    // Ngăn chặn code phía dưới tiếp tục chạy để tránh vỡ giao diện hệ thống
    die();
}
?>