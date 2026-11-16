<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Sales History';
$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to']   ?? date('Y-m-d');
$sales = rows($conn, "SELECT * FROM sales WHERE DATE(sale_date) BETWEEN ? AND ? ORDER BY id DESC", [$from, $to]);
$sum = array_sum(array_column($sales, 'total'));
include __DIR__ . '/../includes/header.php';
?>
<h4 class="mb-3">Sales History</h4>
<form class="row g-2 mb-3">
  <div class="col-auto"><input type="date" name="from" class="form-control" value="<?= e($from) ?>"></div>
  <div class="col-auto"><input type="date" name="to" class="form-control" value="<?= e($to) ?>"></div>
  <div class="col-auto"><button class="btn btn-dark">Filter</button></div>
</form>
<table class="table table-sm table-bordered bg-white">
  <thead><tr><th>Invoice</th><th>Date</th><th>Customer</th><th>Method</th><th class="text-end">Discount</th><th class="text-end">Total</th><th></th></tr></thead>
  <?php foreach ($sales as $s): ?>
  <tr><td><?= e($s['invoice_no']) ?></td><td><?= e($s['sale_date']) ?></td><td><?= e($s['customer_name'] ?? '-') ?></td>
    <td><?= e($s['payment_method']) ?></td><td class="text-end"><?= money($s['discount']) ?></td><td class="text-end"><?= money($s['total']) ?></td>
    <td><a class="btn btn-sm btn-outline-secondary" href="view.php?id=<?= $s['id'] ?>">View</a></td></tr>
  <?php endforeach; if (!$sales) echo '<tr><td colspan="7" class="text-center text-muted">No sales in this range</td></tr>'; ?>
  <tfoot><tr class="fw-bold"><td colspan="5" class="text-end"><?= count($sales) ?> bills — Total</td><td class="text-end"><?= money($sum) ?></td><td></td></tr></tfoot>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
