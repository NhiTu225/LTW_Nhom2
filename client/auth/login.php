<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . "config DATABASE HERE";
$conn = $pdo;

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $message = "Vui lòng nhập email và mật khẩu";
    } else {
        $sql = "SELECT * FROM account WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":email" => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id_acc"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                header("Location: ../index.php");
                exit;
            }
        } else {
            $message = "Email hoặc mật khẩu không đúng";
        }
    }
}
?>

