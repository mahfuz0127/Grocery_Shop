<?php
require_once __DIR__ . '/includes/auth.php';
$title = 'Dashboard';
$today = date('Y-m-d');
$t = row($conn, "SELECT COUNT(*) bills, COALESCE(SUM(total),0) amt FROM sales WHERE DATE(sale_date)=?", [$today]);
$m = row($conn, "SELECT COALESCE(SUM(total),0) amt FROM sales WHERE DATE_FORMAT(sale_date,'%Y-%m')=?", [date('Y-m')]);
$p = row($conn, "SELECT COUNT(*) c FROM products");
$low = rows($conn, "SELECT name, stock, unit FROM products WHERE stock<=? ORDER BY stock LIMIT 10", [(float)LOW_STOCK]);
$recent = rows($conn, "SELECT id, invoice_no, total, sale_date FROM sales ORDER BY id DESC LIMIT 8");
include __DIR__ . '/includes/header.php';
?>
<h4 class="mb-3">Dashboard</h4>
<div class="row g-3 mb-4">
  <?php foreach ([['Today Sales', money($t['amt'])], ['Today Bills', $t['bills']], ['This Month', money($m['amt'])], ['Products', $p['c']]] as [$l, $v]): ?>
  <div class="col-6 col-lg-3"><div class="card"><div class="card-body">
    <div class="text-muted small"><?= $l ?></div><div class="fs-4"><?= $v ?></div>
  </div></div></div>
  <?php endforeach; ?>
</div>
<div class="row g-3">
  <div class="col-lg-6"><div class="card"><div class="card-header">Low Stock (≤ <?= LOW_STOCK ?>)</div>
    <table class="table table-sm mb-0"><?php foreach ($low as $r): ?>
      <tr><td><?= e($r['name']) ?></td><td class="text-end low"><?= qty($r['stock']) . ' ' . e($r['unit']) ?></td></tr>
    <?php endforeach; if (!$low) echo '<tr><td class="text-muted">All good</td></tr>'; ?></table></div></div>
  <div class="col-lg-6"><div class="card"><div class="card-header">Recent Sales</div>
    <table class="table table-sm mb-0"><?php foreach ($recent as $r): ?>
      <tr><td><a href="sales/view.php?id=<?= $r['id'] ?>"><?= e($r['invoice_no']) ?></a></td>
      <td><?= e($r['sale_date']) ?></td><td class="text-end"><?= money($r['total']) ?></td></tr>
    <?php endforeach; if (!$recent) echo '<tr><td class="text-muted">No sales yet</td></tr>'; ?></table></div></div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
