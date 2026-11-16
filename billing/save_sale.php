<?php
require_once __DIR__ . '/../includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) redirect('pos.php');

$customer = trim($_POST['customer'] ?? '');
$method   = in_array($_POST['method'] ?? '', ['Cash', 'Card', 'Mobile']) ? $_POST['method'] : 'Cash';

mysqli_begin_transaction($conn);
try {
    $lines = []; $sub = 0;
    foreach ($_SESSION['cart'] as $pid => $q) {
        $q = (float)$q;
        $p = row($conn, "SELECT * FROM products WHERE id=? FOR UPDATE", [(int)$pid]);   // lock row
        if (!$p) throw new Exception('A product in the cart no longer exists');
        if ($q <= 0 || $q > (float)$p['stock']) throw new Exception('Insufficient stock for ' . $p['name']);
        $line = round($p['sell_price'] * $q, 2);
        $sub += $line;
        $lines[] = [$p, $q, $line];
    }
    $sub      = round($sub, 2);
    $discount = min(max((float)($_POST['discount'] ?? 0), 0), $sub);
    $total    = round($sub - $discount, 2);
    $paid     = ($_POST['paid'] ?? '') === '' ? $total : (float)$_POST['paid'];
    if ($paid < $total) throw new Exception('Amount paid is less than the total');
    $change   = round($paid - $total, 2);

    $inv = invoice_no($conn);
    run($conn, "INSERT INTO sales (invoice_no, customer_name, subtotal, discount, total, paid, change_due, payment_method)
                VALUES (?,?,?,?,?,?,?,?)", [$inv, $customer ?: null, $sub, $discount, $total, $paid, $change, $method]);
    $sid = (int)mysqli_insert_id($conn);

    foreach ($lines as [$p, $q, $line]) {
        run($conn, "INSERT INTO sale_items (sale_id, product_id, name, qty, cost_price, price, total) VALUES (?,?,?,?,?,?,?)",
            [$sid, (int)$p['id'], $p['name'], $q, (float)$p['cost_price'], (float)$p['sell_price'], $line]);
        run($conn, "UPDATE products SET stock = stock - ? WHERE id=?", [$q, (int)$p['id']]);
    }
    mysqli_commit($conn);
    $_SESSION['cart'] = [];
    redirect(BASE_URL . '/invoice/view.php?id=' . $sid . '&print=1');
} catch (Throwable $e) {
    mysqli_rollback($conn);
    flash($e->getMessage(), 'danger');
    redirect('pos.php');
}
