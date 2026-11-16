<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Invoice';
$id = (int)($_GET['id'] ?? 0);
$s = row($conn, "SELECT * FROM sales WHERE id=?", [$id]);
if (!$s) { flash('Invoice not found', 'danger'); redirect(BASE_URL . '/sales/index.php'); }
$items = rows($conn, "SELECT * FROM sale_items WHERE sale_id=?", [$id]);
include __DIR__ . '/../includes/header.php';
?>
<div class="no-print mb-3">
  <button onclick="window.print()" class="btn btn-dark btn-sm">Print</button>
  <a href="<?= BASE_URL ?>/billing/pos.php" class="btn btn-outline-dark btn-sm">New Sale</a>
  <a href="<?= BASE_URL ?>/sales/index.php" class="btn btn-light btn-sm">Sales History</a>
</div>
<div class="bill">
  <div class="text-center mb-2"><h5 class="mb-0"><?= e(SHOP_NAME) ?></h5><small class="text-muted">Sales Invoice</small></div>
  <div class="d-flex justify-content-between small"><span>No: <?= e($s['invoice_no']) ?></span><span><?= e($s['sale_date']) ?></span></div>
  <?php if ($s['customer_name']): ?><div class="small">Customer: <?= e($s['customer_name']) ?></div><?php endif; ?>
  <table class="table table-sm mt-2">
    <thead><tr><th>Item</th><th class="text-end">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>
    <?php foreach ($items as $i): ?>
    <tr><td><?= e($i['name']) ?></td><td class="text-end"><?= qty($i['qty']) ?></td>
      <td class="text-end"><?= money($i['price']) ?></td><td class="text-end"><?= money($i['total']) ?></td></tr>
    <?php endforeach; ?>
  </table>
  <div class="d-flex justify-content-between"><span>Subtotal</span><span><?= money($s['subtotal']) ?></span></div>
  <div class="d-flex justify-content-between"><span>Discount</span><span>- <?= money($s['discount']) ?></span></div>
  <div class="d-flex justify-content-between fw-bold fs-6"><span>Total</span><span><?= money($s['total']) ?></span></div>
  <div class="d-flex justify-content-between"><span>Paid (<?= e($s['payment_method']) ?>)</span><span><?= money($s['paid']) ?></span></div>
  <div class="d-flex justify-content-between"><span>Change</span><span><?= money($s['change_due']) ?></span></div>
  <p class="text-center small text-muted mt-3 mb-0">Thank you for shopping with us!</p>
</div>
<?php if (!empty($_GET['print'])): ?><script>window.addEventListener('load', () => setTimeout(() => window.print(), 300));</script><?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
