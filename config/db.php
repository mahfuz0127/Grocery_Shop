<?php
if (session_status() === PHP_SESSION_NONE) session_start();

define('BASE_URL', '/Grocery');          // folder name under htdocs
define('SHOP_NAME', 'My Grocery Store');
define('LOW_STOCK', 5);                  // low-stock warning level
date_default_timezone_set('Asia/Dhaka');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_connect('localhost', 'root', '', 'grocery');
    mysqli_set_charset($conn, 'utf8mb4');
} catch (Throwable $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}
