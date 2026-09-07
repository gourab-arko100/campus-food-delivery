<?php
require_once __DIR__ . '/../config/config.php';
require_admin();

$stats = [
 'customers' => (int)db()->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn(),
 'menu' => (int)db()->query("SELECT COUNT(*) FROM menu")->fetchColumn(),
 'orders' => (int)db()->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
 'sales' => (float)db()->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status <> 'cancelled'")->fetchColumn(),
];
$page_title='Admin Dashboard';
require __DIR__ . '/../includes/header.php';
?>
<h2>Admin Dashboard</h2>
<div class="row g-3 mb-4">
<?php foreach ([['Customers',$stats['customers']],['Menu Items',$stats['menu']],['Orders',$stats['orders']],['Sales',money($stats['sales'])]] as $s): ?>
<div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary"><?= $s[0] ?></div><div class="fs-3 fw-bold"><?= $s[1] ?></div></div></div></div>
<?php endforeach; ?>
</div>
<a class="btn btn-dark me-2" href="<?= url('admin/menu.php') ?>">Manage Menu</a>
<a class="btn btn-outline-dark" href="<?= url('admin/orders.php') ?>">Manage Orders</a>
<?php require __DIR__ . '/../includes/footer.php'; ?>
