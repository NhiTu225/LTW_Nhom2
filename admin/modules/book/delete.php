<?php
// Tự động dò tìm file cấu hình DB thông minh bằng logic định vị thư mục của em
$admin_path = dirname(__DIR__, 2);
$db_admin = $admin_path . DIRECTORY_SEPARATOR . 'db.php';
$db_config = dirname($admin_path) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db.php';

if (file_exists($db_admin)) {
    include_once $db_admin;
} else if (file_exists($db_config)) {
    include_once $db_config;
}

if (!isset($pdo)) {
    die("Hệ thống không tìm thấy biến kết nối PDO (\$pdo)!");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // 1. Lấy thông tin sách trước khi xóa để hiển thị lên popup
        $stmt_book = $pdo->prepare("SELECT title, image FROM books WHERE id = ?");
        $stmt_book->execute([$id]);
        $book = $stmt_book->fetch(PDO::FETCH_ASSOC);
        
        if ($book) {
            $book_title = $book['title'];
            $img_name = $book['image'];
            
            // Dọn dẹp ảnh vật lý
            $upload_dir = "../../../public/upload/"; 
            if ($img_name && $img_name != 'default.jpg' && file_exists($upload_dir . $img_name)) {
                unlink($upload_dir . $img_name);
            }

            // 2. Thực thi xóa bản ghi khỏi DB
            $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
            $stmt->execute([$id]);

            // 🌟 IN RA POPUP DIV XUYÊN THẤU VÀ TỰ ĐỘNG CHUYỂN HƯỚNG
            echo '
            <!DOCTYPE html>
            <html lang="vi">
            <head>
                <meta charset="UTF-8">
                <title>Xóa thành công</title>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
                <style>
                    /* Lớp nền phủ mờ nhìn xuyên thấu phía sau */
                    .popup-overlay {
                        position: fixed;
                        top: 0; left: 0; width: 100%; height: 100%;
                        background: rgba(30, 38, 64, 0.35); /* Màu nền tối nhẹ kết hợp độ trong suốt */
                        backdrop-filter: blur(4px); /* Làm nhòe nhẹ hậu cảnh cực sang trọng */
                        z-index: 9999;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-family: "Segoe UI", system-ui, sans-serif;
                    }
                    /* Hộp thoại Popup nằm chính giữa màn hình */
                    .popup-box {
                        background: rgba(255, 255, 255, 0.95);
                        padding: 2rem 2.5rem;
                        border-radius: 1.25rem;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                        text-align: center;
                        max-width: 420px;
                        width: 90%;
                        animation: scaleUp 0.3s ease-in-out forwards;
                    }
                    .popup-icon {
                        width: 60px; height: 60px;
                        background: #d1e7dd; color: #0f5132;
                        font-size: 1.75rem;
                        border-radius: 50%;
                        display: flex; align-items: center; justify-content: center;
                        margin: 0 auto 1.25rem;
                    }
                    .popup-title { margin: 0 0 0.5rem; color: #1e2640; font-size: 1.35rem; fw-bold; }
                    .popup-message { margin: 0 0 1.5rem; color: #6c757d; font-size: 0.95rem; line-height: 1.5; }
                    .popup-book-name { color: #0d6efd; font-weight: 600; display: block; margin-top: 0.25rem; }
                    .popup-btn {
                        background: #0d6efd; color: #fff; border: none;
                        padding: 0.65rem 2rem; border-radius: 0.5rem;
                        font-weight: 600; cursor: pointer; font-size: 0.95rem;
                        transition: all 0.2s ease;
                    }
                    .popup-btn:hover { background: #0b5ed7; }
                    
                    @keyframes scaleUp {
                        0% { transform: scale(0.8); opacity: 0; }
                        100% { transform: scale(1); opacity: 1; }
                    }
                </style>
            </head>
            <body>
                <div class="popup-overlay">
                    <div class="popup-box">
                        <div class="popup-icon"><i class="fa-solid fa-circle-check"></i></div>
                        <h4 class="popup-title">Xóa thành công!</h4>
                        <p class="popup-message">Hệ thống đã dọn dẹp và xóa hoàn toàn: <span class="popup-book-name">「' . htmlspecialchars($book_title) . '」</span></p>
                        <button class="popup-btn" onclick="goBack()">Xác nhận</button>
                    </div>
                </div>

                <script>
                    const autoRedirect = setTimeout(goBack, 3500);

                    function goBack() {
                        clearTimeout(autoRedirect);
                        window.location.href = "../../index.php?page=book-list";
                    }
                </script>
            </body>
            </html>';
            exit();
        } else {
            echo "<script>window.location.href='../../index.php?page=book-list';</script>";
            exit();
        }

    } catch (Exception $e) {
        die("Lỗi Database khi xóa sách: " . $e->getMessage());
    }
}

echo "<script>window.location.href='../../index.php?page=book-list';</script>";
exit();
?>