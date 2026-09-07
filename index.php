<?php
require_once __DIR__ . '/config/config.php';

$page_title = 'CampusEats — Food Delivery';
$stmt = db()->query('SELECT * FROM menu WHERE available = 1 ORDER BY id DESC');
$menu = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<section class="hero p-5 mb-4">
  <div class="row align-items-center">
    <div class="col-md-8">
      <h1 class="display-5 fw-bold">Campus food, delivered simply.</h1>
      <p class="lead mb-0">Browse the menu, add your favourites to the cart and place an order.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
      <?php if (!is_logged_in()): ?><a class="btn btn-warning btn-lg" href="<?= url('auth/register.php') ?>">Get Started</a><?php endif; ?>
    </div>
  </div>
</section>

<h2 class="mb-3">Today's Menu</h2>
<div class="row g-4">
<?php foreach ($menu as $item): ?>
  <div class="col-sm-6 col-lg-4">
    <div class="card food-card h-100 border-0 shadow-sm">
      <?php if ($item['image']): ?>
        <img src="<?= e($item['image']) ?>" class="card-img-top food-img" alt="<?= e($item['name']) ?>">
      <?php else: ?>
        <div class="food-img d-flex align-items-center justify-content-center fs-1">🍽️</div>
      <?php endif; ?>
      <div class="card-body d-flex flex-column">
        <h5><?= e($item['name']) ?></h5>
        <p class="text-secondary"><?= e($item['description']) ?></p>
        <div class="mt-auto d-flex justify-content-between align-items-center">
          <span class="price"><?= money((float)$item['price']) ?></span>
          <?php if (is_logged_in()): ?>
          <form method="post" action="<?= url('customer/cart.php') ?>">
            <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="menu_id" value="<?= (int)$item['id'] ?>">
            <button class="btn btn-dark">Add to Cart</button>
          </form>
          <?php else: ?><a href="<?= url('auth/login.php') ?>" class="btn btn-outline-dark">Login to order</a><?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
