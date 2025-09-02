<?php
// db.php - DB connection used by every file
session_start();

$DB_HOST = 'localhost';
$DB_NAME = 'dbsxq2efmawfkk';
$DB_USER = 'ud89fw4spumtd';
$DB_PASS = 'dpnpg9ge2uey';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    // show a friendly error
    echo "<h2>Database connection failed.</h2><p>".$e->getMessage()."</p>";
    exit;
}

function isLoggedIn(){
    return !empty($_SESSION['user']);
}
function currentUser(){
    return $_SESSION['user'] ?? null;
}
?>
