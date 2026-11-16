<?php
require_once __DIR__ . '/../includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    run($conn, "DELETE FROM categories WHERE id=?", [(int)($_POST['id'] ?? 0)]);
    flash('Category deleted (its products are now uncategorised)');
}
redirect('index.php');
