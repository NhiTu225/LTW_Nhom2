<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../config/db_copy.php";
$conn = $pdo;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($username) || empty($email) || empty($password) || empty($fullname)) {
        $message = "Vui lòng nhập đầy đủ thông tin";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try {
            $sql = "INSERT INTO account (username, fullname, email, password, role, created_at)
                    VALUES (:username, :fullname, :email, :password, 'user', NOW())";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ":username" => $username,
                ":fullname" => $fullname,
                ":email" => $email,
                ":password" => $hashedPassword
            ]);

            $message = "Đăng ký thành công. Bạn có thể đăng nhập.";

        } catch (PDOException $e) {
            $message = "Lỗi đăng ký: " . $e->getMessage();
        }
    }
}
