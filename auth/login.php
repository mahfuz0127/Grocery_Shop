<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
if (!empty($_SESSION['admin_id'])) redirect(BASE_URL . '/dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $a = row($conn, "SELECT * FROM admins WHERE username=?", [$u]);
    if ($a && password_verify($_POST['password'] ?? '', $a['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $a['id'];
        $_SESSION['admin_name'] = $a['username'];
        redirect(BASE_URL . '/dashboard.php');
    }
    $error = 'Invalid username or password';
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width:360px; margin-top:12vh">
  <div class="card"><div class="card-body p-4">
    <h5 class="text-center mb-3"><?= e(SHOP_NAME) ?></h5>
    <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <input class="form-control mb-2" name="username" placeholder="Username" required autofocus>
      <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
      <button class="btn btn-dark w-100">Login</button>
    </form>
  </div></div>
</div>
</body></html>
