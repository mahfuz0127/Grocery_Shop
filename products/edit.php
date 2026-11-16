<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Edit Product';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$p = row($conn, "SELECT * FROM products WHERE id=?", [$id]);
if (!$p) { flash('Product not found', 'danger'); redirect('index.php'); }
$cats = rows($conn, "SELECT * FROM categories ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = array_merge($p, array_intersect_key($_POST, $p));
    if (trim($p['name']) === '') flash('Name is required', 'danger');
    elseif ((float)$p['sell_price'] < 0 || (float)$p['cost_price'] < 0 || (float)$p['stock'] < 0) flash('Values cannot be negative', 'danger');
    else {
        $catId = $p['category_id'] === '' ? null : (int)$p['category_id'];
        run($conn, "UPDATE products SET category_id=?, name=?, barcode=?, unit=?, cost_price=?, sell_price=?, stock=? WHERE id=?",
            [$catId, trim($p['name']), trim($p['barcode']) ?: null, trim($p['unit']) ?: 'pcs',
             (float)$p['cost_price'], (float)$p['sell_price'], (float)$p['stock'], $id]);
        flash('Product updated'); redirect('index.php');
    }
}
include __DIR__ . '/../includes/header.php';
?>
<h4 class="mb-3">Edit Product</h4>
<form method="post" class="card card-body" style="max-width:600px">
  <input type="hidden" name="id" value="<?= $id ?>">
  <div class="row g-2">
    <div class="col-md-8"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= e($p['name']) ?>" required></div>
    <div class="col-md-4"><label class="form-label">Barcode</label><input class="form-control" name="barcode" value="<?= e($p['barcode']) ?>"></div>
    <div class="col-md-6"><label class="form-label">Category</label><select class="form-select" name="category_id"><option value="">— none —</option>
      <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $p['category_id'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></div>
    <div class="col-md-6"><label class="form-label">Unit</label><input class="form-control" name="unit" value="<?= e($p['unit']) ?>"></div>
    <div class="col-md-4"><label class="form-label">Cost price</label><input class="form-control" type="number" step="0.01" min="0" name="cost_price" value="<?= e($p['cost_price']) ?>" required></div>
    <div class="col-md-4"><label class="form-label">Sell price</label><input class="form-control" type="number" step="0.01" min="0" name="sell_price" value="<?= e($p['sell_price']) ?>" required></div>
    <div class="col-md-4"><label class="form-label">Stock</label><input class="form-control" type="number" step="0.01" min="0" name="stock" value="<?= e($p['stock']) ?>" required></div>
  </div>
  <div class="mt-3"><button class="btn btn-dark">Update</button> <a href="index.php" class="btn btn-light">Cancel</a></div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
