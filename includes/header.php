<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Grocery') ?> - <?= e(SHOP_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark px-3 no-print">
  <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard.php"><?= e(SHOP_NAME) ?></a>
  <span class="text-light small"><?= e($_SESSION['admin_name'] ?? '') ?> |
    <a class="text-light" href="<?= BASE_URL ?>/auth/logout.php">Logout</a></span>
</nav>
<div class="container-fluid">
<div class="row">
<?php include __DIR__ . '/sidebar.php'; ?>
<main class="col-md-10 p-4">
<?= flash() ?>
