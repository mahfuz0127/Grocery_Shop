<?php
require_once __DIR__ . '/../includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    run($conn, "DELETE FROM products WHERE id=?", [(int)($_POST['id'] ?? 0)]);
    flash('Product deleted (past bills are kept)');
}
redirect('index.php');
