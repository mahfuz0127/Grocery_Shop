<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';
if (empty($_SESSION['admin_id'])) redirect(BASE_URL . '/auth/login.php');
