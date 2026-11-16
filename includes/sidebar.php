<?php
$links = [
  'dashboard.php'              => 'Dashboard',
  'billing/pos.php'            => 'New Sale (POS)',
  'products/index.php'         => 'Products',
  'categories/index.php'       => 'Categories',
  'sales/index.php'            => 'Sales History',
  'reports/profit_loss.php'    => 'Profit / Loss',
  'auth/change_password.php'   => 'Change Password',
];
$here = $_SERVER['SCRIPT_NAME'];
?>
<nav class="col-md-2 sidebar no-print p-0">
  <div class="list-group list-group-flush">
    <?php foreach ($links as $path => $label): ?>
      <a class="list-group-item list-group-item-action <?= substr($here, -strlen($path)) === $path ? 'active' : '' ?>"
         href="<?= BASE_URL . '/' . $path ?>"><?= $label ?></a>
    <?php endforeach; ?>
  </div>
</nav>
