<?php
require_once __DIR__ . '/../config/config.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title ?? 'Campus Food Delivery') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('assets/style.css') ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= url('index.php') ?>">CampusEats</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="<?= url('index.php') ?>">Menu</a></li>
        <?php if (is_logged_in()): ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('customer/cart.php') ?>">Cart <span class="badge bg-warning text-dark"><?= cart_count() ?></span></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= url('customer/orders.php') ?>">My Orders</a></li>
          <?php if (is_admin()): ?><li class="nav-item"><a class="nav-link" href="<?= url('admin/index.php') ?>">Admin</a></li><?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('auth/logout.php') ?>">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('auth/login.php') ?>">Login</a></li>
          <li class="nav-item"><a class="btn btn-warning ms-lg-2" href="<?= url('auth/register.php') ?>">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">
<?php if ($msg = flash('success')): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<?php if ($msg = flash('error')): ?><div class="alert alert-danger"><?= e($msg) ?></div><?php endif; ?>
