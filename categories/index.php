<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Categories';
$cats = rows($conn, "SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id=c.id) cnt FROM categories c ORDER BY c.name");
include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between mb-3">
  <h4>Categories</h4><a href="add.php" class="btn btn-dark btn-sm">+ Add Category</a>
</div>
<table class="table table-sm table-bordered bg-white">
  <thead><tr><th>#</th><th>Name</th><th>Products</th><th width="140"></th></tr></thead>
  <?php foreach ($cats as $c): ?>
  <tr><td><?= $c['id'] ?></td><td><?= e($c['name']) ?></td><td><?= $c['cnt'] ?></td>
    <td><a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= $c['id'] ?>">Edit</a>
    <form method="post" action="delete.php" class="d-inline" onsubmit="return confirm('Delete this category?')">
      <input type="hidden" name="id" value="<?= $c['id'] ?>"><button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
  <?php endforeach; if (!$cats) echo '<tr><td colspan="4" class="text-center text-muted">No categories</td></tr>'; ?>
</table>
<?php include __DIR__ . '/../includes/footer.php'; ?>
