<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Bill Details';
$id = (int)($_GET['id'] ?? 0);
$s = row($conn, "SELECT * FROM sales WHERE id=?", [$id]);
if (!$s) { flash('Bill not found', 'danger'); redirect('index.php'); }
$items = rows($conn, "SELECT * FROM sale_items WHERE sale_id=?", [$id]);
include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between mb-3">
  <h4><?= e($s['invoice_no']) ?></h4>
  <div><a href="<?= BASE_URL ?>/invoice/view.php?id=<?= $id ?>" class="btn btn-dark btn-sm">Printable Bill</a>
       <a href="index.php" class="btn btn-light btn-sm">Back</a></div>
</div>
<div class="card card-body mb-3 small">
  Date: <?= e($s['sale_date']) ?> | Customer: <?= e($s['customer_name'] ?? '-') ?> | Payment: <?= e($s['payment_method']) ?>
</div>
<table class="table table-sm table-bordered bg-white">
  <thead><tr><th>Item</th><th class="text-end">Qty</th><th class="text-end">Cost</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>
  <?php foreach ($items as $i): ?>
  <tr><td><?= e($i['name']) ?></td><td class="text-end"><?= qty($i['qty']) ?></td><td class="text-end"><?= money($i['cost_price']) ?></td>
    <td class="text-end"><?= money($i['price']) ?></td><td class="text-end"><?= money($i['total']) ?></td></tr>
  <?php endforeach; ?>
  <tfoot>
    <tr><td colspan="4" class="text-end">Subtotal</td><td class="text-end"><?= money($s['subtotal']) ?></td></tr>
    <tr><td colspan="4" class="text-end">Discount</td><td class="text-end"><?= money($s['discount']) ?></td></tr>
    <tr class="fw-bold"><td colspan="4" class="text-end">Total</td><td class="text-end"><?= money($s['total']) ?></td></tr>
    <tr><td colspan="4" class="text-end">Paid / Change</td><td class="text-end"><?= money($s['paid']) ?> / <?= money($s['change_due']) ?></td></tr>
  </tfoot>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
