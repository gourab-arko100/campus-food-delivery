<?php
require_once __DIR__ . '/../config/config.php';
require_login();
$orderId = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT o.*,p.payment_option,p.status payment_status,p.transaction_ref FROM orders o LEFT JOIN payments p ON p.order_id=o.id WHERE o.id=? AND o.user_id=?');
$stmt->execute([$orderId,$_SESSION['user_id']]);
$order = $stmt->fetch();
if (!$order) redirect('customer/orders.php');

$page_title = 'Order Confirmed';
require __DIR__ . '/../includes/header.php';
?>
<div class="text-center py-5">
<div class="display-3">✅</div>
<h1>Order placed successfully!</h1>
<p class="lead">Order #<?= $order['id'] ?> — <?= money((float)$order['total_amount']) ?></p>
<p>Payment: <?= e(ucwords(str_replace('_',' ',$order['payment_option']))) ?> · <?= e($order['payment_status']) ?></p>
<a class="btn btn-dark" href="<?= url('customer/orders.php') ?>">View My Orders</a>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
