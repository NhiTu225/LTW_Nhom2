<?php
// FILE CẤU HÌNH KẾT NỐI DATABASE XAMPP

$host = '127.0.0.1'; 
$dbname = 'ltw_nhom2';
$username = 'root';
$password = ''; 

try {
    // 1. KẾT NỐI VỚI MYSQL THẬT TRÊN XAMPP
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
        $username, 
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    // In thông báo kết nối hoàn tất
    // echo "
    // <div style='max-width: 600px; margin: 20px auto; padding: 20px; border-left: 5px solid #2e7d32; background-color: #e8f5e9; font-family: sans-serif; border-radius: 4px;'>
    //     <h3 style='color: #2e7d32; margin: 0;'>✅ KẾT NỐI DATABASE XAMPP THÀNH CÔNG!</h3>
    //     <p style='color: #555; margin: 5px 0 0 0; font-size: 0.9rem;'>Hệ thống đang chạy trên cơ sở dữ liệu thật của nhóm: <strong>$dbname</strong></p>
    // </div>
    // ";

} catch (PDOException $e) {
    $errorCode = $e->getCode();
    $errorMessage = $e->getMessage();

    // 2. LỖI
    if ($errorCode == 2002) {
        // TRƯỜNG HỢP: CHƯA BẬT MYSQL TRÊN XAMPP -> BẮT BUỘC DỪNG VÀ BÁO LỖI ĐỎ
        echo "
        <div style='max-width: 600px; margin: 20px auto; padding: 25px; border-left: 5px solid #cd1818; background-color: #fff5f5; font-family: sans-serif; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
            <h3 style='color: #cd1818; margin-top: 0;'>⚠️ LỖI MÔI TRƯỜNG: CHƯA BẬT XAMPP MYSQL</h3>
            <p style='color: #555; font-size: 0.95rem; line-height: 1.6;'>
                <strong>Chi tiết lỗi:</strong> <code style='background: #eee; padding: 2px 6px; border-radius: 3px; color: #c7254e;'>$errorMessage</code>
            </p>
            <hr style='border: 0; border-top: 1px solid #ffebeb; margin: 15px 0;'>
            <p style='color: #2e7d32; font-size: 0.9rem; margin-bottom: 0;'>
                💡 <strong>Gợi ý khắc phục:</strong> Hãy mở <strong>XAMPP Control Panel</strong> lên và bấm nút <strong>Start</strong> bên cạnh dịch vụ MySQL để kích hoạt server nhé!
            </p>
        </div>
        ";
        die(); // Dừng hệ thống, bắt buộc người dùng phải bật XAMPP

    } elseif ($errorCode == 1049) {
        // TRƯỜNG HỢP: ĐÃ BẬT XAMPP NHƯNG CHƯA TẠO DATABASE -> KÍCH HOẠT HỆ THỐNG DỰ PHÒNG SQLite
        try {
            $pdo = new PDO('sqlite::memory:');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Khởi tạo cấu trúc bảng ảo
            $pdo->exec("CREATE TABLE IF NOT EXISTS books (
                id INTEGER PRIMARY KEY AUTOINCREMENT, 
                title TEXT,
                price REAL,
                old_price REAL,
                image TEXT,
                sale TEXT,
                category TEXT,
                is_flashsale INTEGER DEFAULT 0
            )");

            // Đổ dữ liệu Mockup dự phòng vào hệ thống ảo
            $mock_books = [
                ['Sự Mệnh Hail Mary - Project Hail Mary', 125400, 300000, 'Hail+Mary', '40%', 'it', 1],
                ['Linh Tính Và Lý Trí - Các Tiểu Luận Phê Bình', 77000, 110000, 'linh_tinh-li_tri-cac_tieu_luan_pb.png', '30%', 'skills', 1],
                ['Không Gia Đình - Nobody\'s Boy - Tập 1', 67000, 85000, 'Khong+Gia+Dinh+1', '21%', 'skills', 1],
                ['The Wonderful Wizard of Oz - Phù Thủy Xứ Oz', 67000, 148000, 'Wizard+Oz', '55%', 'it', 1],
                ['Kinh Tế Vĩ Mô Cho Người Khởi Nghiệp', 180000, 240000, 'Economics', '25%', 'economy', 0],
                ['Tâm Lý Học Hành Vi Khách Hàng', 115000, 170000, 'Psychology', '32%', 'economy', 0],
                ['Sách Lập Trình PHP Cho Người Lười', 150000, 250000, 'PHP_Book', '40%', 'it', 0]
            ];

            $insert_sql = "INSERT INTO books (title, price, old_price, image, sale, category, is_flashsale) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($insert_sql);
            foreach ($mock_books as $b) {
                $stmt->execute($b);
            }

            // In cảnh báo màu vàng thông báo đang dùng dữ liệu ảo để tránh vỡ giao diện web
            echo "
            <div style='max-width: 600px; margin: 20px auto; padding: 20px; border-left: 5px solid #f57c00; background-color: #fff3e0; font-family: sans-serif; border-radius: 4px;'>
                <h3 style='color: #e65100; margin: 0;'>⚠️ CẢNH BÁO: CHƯA CÓ DATABASE THẬT</h3>
                <p style='color: #555; margin: 5px 0 0 0; font-size: 0.9rem;'>
                    Không tìm thấy cơ sở dữ liệu <strong>'$dbname'</strong>. Hệ thống tự động kích hoạt chế độ <strong>Dữ liệu ảo dự phòng (SQLite)</strong> để giữ giao diện UI không bị sập lỗi.
                </p>
                <small style='color: #d84315;'>👉 Vui lòng vào phpMyAdmin để tạo database và import file SQL của nhóm!</small>
            </div>
            ";

        } catch (PDOException $sqlite_e) {
            die("Không thể khởi tạo cơ sở dữ liệu dự phòng: " . $sqlite_e->getMessage());
        }
    } else {
        // CÁC LỖI KHÁC (Sai tài khoản, mật khẩu...)
        die("<div style='color:red; padding:20px;'>Lỗi hệ thống cơ sở dữ liệu: " . $errorMessage . "</div>");
    }
}