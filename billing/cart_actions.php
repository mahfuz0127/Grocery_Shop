<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart'] = [];
$a   = $_POST['action'] ?? 'cart';
$id  = (int)($_POST['id'] ?? 0);
$err = '';

if ($a === 'search') {
    $q = trim($_POST['q'] ?? '');
    $list = rows($conn, "SELECT id, name, barcode, sell_price, stock, unit FROM products
                         WHERE stock>0 AND (name LIKE ? OR barcode LIKE ?)
                         ORDER BY (barcode=?) DESC, name LIMIT 20", ["%$q%", "%$q%", $q]);
    echo json_encode(['products' => $list]);
    exit;
}

if (in_array($a, ['add', 'update']) && $id) {
    $p = row($conn, "SELECT stock, name FROM products WHERE id=?", [$id]);
    if (!$p) {
        $err = 'Product not found';
    } else {
        $new = $a === 'add' ? ($_SESSION['cart'][$id] ?? 0) + 1 : round((float)($_POST['qty'] ?? 0), 2);
        if ($new <= 0) unset($_SESSION['cart'][$id]);
        elseif ($new > (float)$p['stock']) $err = 'Only ' . qty($p['stock']) . ' in stock for ' . $p['name'];
        else $_SESSION['cart'][$id] = $new;
    }
} elseif ($a === 'remove') {
    unset($_SESSION['cart'][$id]);
} elseif ($a === 'clear') {
    $_SESSION['cart'] = [];
}

// build cart response from fresh DB prices
$items = []; $sub = 0;
foreach ($_SESSION['cart'] as $pid => $q) {
    $p = row($conn, "SELECT id, name, sell_price FROM products WHERE id=?", [(int)$pid]);
    if (!$p) { unset($_SESSION['cart'][$pid]); continue; }
    $line = round($p['sell_price'] * $q, 2);
    $sub += $line;
    $items[] = ['id' => (int)$p['id'], 'name' => $p['name'], 'price' => (float)$p['sell_price'], 'qty' => (float)$q, 'total' => $line];
}
echo json_encode(['items' => $items, 'subtotal' => round($sub, 2), 'error' => $err]);
