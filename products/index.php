<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Products';
$q = trim($_GET['q'] ?? '');
$cat = (int)($_GET['cat'] ?? 0);

$sql = "SELECT p.*, c.name cat FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE 1=1";
$params = [];
if ($q !== '') { $sql .= " AND (p.name LIKE ? OR p.barcode LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }
if ($cat)      { $sql .= " AND p.category_id=?"; $params[] = $cat; }
$products = rows($conn, $sql . " ORDER BY p.name", $params);
$cats = rows($conn, "SELECT * FROM categories ORDER BY name");
include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between mb-3">
  <h4>Products</h4><a href="add.php" class="btn btn-dark btn-sm">+ Add Product</a>
</div>
<form class="row g-2 mb-3">
  <div class="col-md-4"><input class="form-control" name="q" placeholder="Search name / barcode" value="<?= e($q) ?>"></div>
  <div class="col-md-3"><select class="form-select" name="cat"><option value="0">All categories</option>
    <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
  </select></div>
  <div class="col-auto"><button class="btn btn-dark">Filter</button> <a href="index.php" class="btn btn-light">Reset</a></div>
</form>
<div class="table-responsive">
<table class="table table-sm table-bordered bg-white align-middle">
  <thead><tr><th>#</th><th>Name</th><th>Barcode</th><th>Category</th><th class="text-end">Cost</th><th class="text-end">Price</th><th class="text-end">Stock</th><th width="140"></th></tr></thead>
  <?php foreach ($products as $p): ?>
  <tr><td><?= $p['id'] ?></td><td><?= e($p['name']) ?></td><td><?= e($p['barcode']) ?></td><td><?= e($p['cat'] ?? '-') ?></td>
    <td class="text-end"><?= money($p['cost_price']) ?></td><td class="text-end"><?= money($p['sell_price']) ?></td>
    <td class="text-end <?= $p['stock'] <= LOW_STOCK ? 'low' : '' ?>"><?= qty($p['stock']) . ' ' . e($p['unit']) ?></td>
    <td><a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= $p['id'] ?>">Edit</a>
    <form method="post" action="delete.php" class="d-inline" onsubmit="return confirm('Delete this product?')">
      <input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
  <?php endforeach; if (!$products) echo '<tr><td colspan="8" class="text-center text-muted">No products found</td></tr>'; ?>
</table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
