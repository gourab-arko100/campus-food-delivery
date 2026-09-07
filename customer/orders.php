<?php
require_once __DIR__ . '/../config/config.php';
require_login();

$stmt = db()->prepare('SELECT o.*,p.payment_option,p.status payment_status FROM orders o LEFT JOIN payments p ON p.order_id=o.id WHERE o.user_id=? ORDER BY o.created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$page_title = 'My Orders';
require __DIR__ . '/../includes/header.php';
?>
<h2>My Orders</h2>
<div class="table-responsive"><table class="table bg-white shadow-sm">
<thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Status</th><th>Payment</th></tr></thead>
<tbody>
<?php foreach ($orders as $o): ?>
<tr><td>#<?= $o['id'] ?></td><td><?= e($o['created_at']) ?></td><td><?= money((float)$o['total_amount']) ?></td><td><span class="badge text-bg-secondary"><?= e($o['status']) ?></span></td><td><?= e($o['payment_status'] ?? 'N/A') ?></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<?php if (!$orders): ?><div class="alert alert-info">No orders yet.</div><?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
