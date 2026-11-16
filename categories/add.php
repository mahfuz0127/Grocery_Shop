<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Add Category';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') flash('Name is required', 'danger');
    elseif (row($conn, "SELECT id FROM categories WHERE name=?", [$name])) flash('Category already exists', 'danger');
    else { run($conn, "INSERT INTO categories (name) VALUES (?)", [$name]); flash('Category added'); redirect('index.php'); }
}
include __DIR__ . '/../includes/header.php';
?>
<h4 class="mb-3">Add Category</h4>
<form method="post" class="card card-body" style="max-width:400px">
  <input class="form-control mb-3" name="name" placeholder="Category name" value="<?= e($_POST['name'] ?? '') ?>" required autofocus>
  <div><button class="btn btn-dark">Save</button> <a href="index.php" class="btn btn-light">Cancel</a></div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
