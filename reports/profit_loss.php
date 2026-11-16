<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Profit / Loss';
$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to']   ?? date('Y-m-d');

$days = rows($conn, "SELECT DATE(s.sale_date) d, COUNT(*) bills, SUM(s.discount) disc, SUM(s.total) revenue,
        COALESCE(SUM(c.cogs),0) cogs
    FROM sales s LEFT JOIN (SELECT sale_id, SUM(qty*cost_price) cogs FROM sale_items GROUP BY sale_id) c ON c.sale_id=s.id
    WHERE DATE(s.sale_date) BETWEEN ? AND ? GROUP BY DATE(s.sale_date) ORDER BY d", [$from, $to]);

$tot = ['bills' => 0, 'disc' => 0, 'revenue' => 0, 'cogs' => 0];
foreach ($days as $d) foreach ($tot as $k => $_) $tot[$k] += $d[$k];
$profit = $tot['revenue'] - $tot['cogs'];
$margin = $tot['revenue'] > 0 ? $profit / $tot['revenue'] * 100 : 0;
include __DIR__ . '/../includes/header.php';
?>
<h4 class="mb-3">Profit / Loss Report</h4>
<form class="row g-2 mb-3 no-print">
  <div class="col-auto"><input type="date" name="from" class="form-control" value="<?= e($from) ?>"></div>
  <div class="col-auto"><input type="date" name="to" class="form-control" value="<?= e($to) ?>"></div>
  <div class="col-auto"><button class="btn btn-dark">Show</button> <button type="button" class="btn btn-outline-dark" onclick="print()">Print</button></div>
</form>
<div class="row g-3 mb-4">
  <?php foreach ([['Revenue (after discount)', money($tot['revenue']), ''], ['Cost of goods', money($tot['cogs']), ''],
      [$profit >= 0 ? 'Profit' : 'Loss', money(abs($profit)), $profit >= 0 ? 'text-success' : 'text-danger'],
      ['Margin', number_format($margin, 1) . '%', '']] as [$l, $v, $cls]): ?>
  <div class="col-6 col-lg-3"><div class="card"><div class="card-body">
    <div class="text-muted small"><?= $l ?></div><div class="fs-4 <?= $cls ?>"><?= $v ?></div></div></div></div>
  <?php endforeach; ?>
</div>
<table class="table table-sm table-bordered bg-white">
  <thead><tr><th>Date</th><th class="text-end">Bills</th><th class="text-end">Discount</th><th class="text-end">Revenue</th><th class="text-end">Cost</th><th class="text-end">Profit</th></tr></thead>
  <?php foreach ($days as $d): $p = $d['revenue'] - $d['cogs']; ?>
  <tr><td><?= e($d['d']) ?></td><td class="text-end"><?= $d['bills'] ?></td><td class="text-end"><?= money($d['disc']) ?></td>
    <td class="text-end"><?= money($d['revenue']) ?></td><td class="text-end"><?= money($d['cogs']) ?></td>
    <td class="text-end <?= $p < 0 ? 'text-danger' : '' ?>"><?= money($p) ?></td></tr>
  <?php endforeach; if (!$days) echo '<tr><td colspan="6" class="text-center text-muted">No sales in this range</td></tr>'; ?>
  <tfoot><tr class="fw-bold"><td>Total</td><td class="text-end"><?= $tot['bills'] ?></td><td class="text-end"><?= money($tot['disc']) ?></td>
    <td class="text-end"><?= money($tot['revenue']) ?></td><td class="text-end"><?= money($tot['cogs']) ?></td><td class="text-end"><?= money($profit) ?></td></tr></tfoot>
</table>
<p class="text-muted small">Profit = revenue after discounts − cost price of items sold (cost captured at the time of sale).</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>
