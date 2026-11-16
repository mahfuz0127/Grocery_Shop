<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Edit Category';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$c = row($conn, "SELECT * FROM categories WHERE id=?", [$id]);
if (!$c) { flash('Category not found', 'danger'); redirect('index.php'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') flash('Name is required', 'danger');
    elseif (row($conn, "SELECT id FROM categories WHERE name=? AND id<>?", [$name, $id])) flash('Name already used', 'danger');
    else { run($conn, "UPDATE categories SET name=? WHERE id=?", [$name, $id]); flash('Category updated'); redirect('index.php'); }
}
include __DIR__ . '/../includes/header.php';
?>
<h4 class="mb-3">Edit Category</h4>
<form method="post" class="card card-body" style="max-width:400px">
  <input type="hidden" name="id" value="<?= $id ?>">
  <input class="form-control mb-3" name="name" value="<?= e($_POST['name'] ?? $c['name']) ?>" required>
  <div><button class="btn btn-dark">Update</button> <a href="index.php" class="btn btn-light">Cancel</a></div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
