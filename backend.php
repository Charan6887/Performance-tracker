<?php
session_start();


/* -------------------- BASIC LOGIN CHECK -------------------- */
if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["email"]) ||
    !isset($_SESSION["last_activity"])
) {
    header("Location: login_page.php");
    exit();
}

/* -------------------- SESSION TIMEOUT -------------------- */
$session_timeout = 500; // seconds

if (time() - $_SESSION["last_activity"] > $session_timeout) {
    session_unset();
    session_destroy();
    header("Location: login_page.php?message=Session expired");
    exit();
}

$_SESSION["last_activity"] = time();

/* -------------------- DATABASE CONNECTION -------------------- */
$host = "localhost";
$username = "root";
$password = "";
$dbname = "drezoc_db";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("DB Connection Failed");
}

/* -------------------- VERIFY USER + ROLE -------------------- */
$stmt = $pdo->prepare("
    SELECT id, name, role 
    FROM users 
    WHERE id = ? AND email = ? 
    LIMIT 1
");
$stmt->execute([
    $_SESSION["user_id"],
    $_SESSION["email"]
]);

$user = $stmt->fetch();

/* ❌ User not found */
if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login_page.php");
    exit();
}
?>