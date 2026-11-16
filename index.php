<?php
require_once __DIR__ . '/config/db.php';
header('Location: ' . BASE_URL . (empty($_SESSION['admin_id']) ? '/auth/login.php' : '/dashboard.php'));
exit;
