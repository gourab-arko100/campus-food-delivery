<?php
require_once __DIR__ . '/../config/config.php';
require_login();

$stmt = db()->prepare('SELECT c.id,c.quantity,c.menu_id,m.name,m.price,(c.quantity*m.price) subtotal FROM cart c JOIN menu m ON m.id=c.menu_id WHERE c.user_id=? AND m.available=1');
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();
if (!$items) { flash('error','Your cart is empty.'); redirect('customer/cart.php'); }

$total = array_sum(array_map(fn($x)=>(float)$x['subtotal'], $items));
$stmt = db()->prepare('SELECT * FROM customers WHERE user_id=?');
$stmt->execute([$_SESSION['user_id']]);
$customer = $stmt->fetch();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $paymentOption = $_POST['payment_option'] ?? 'cash_on_delivery';
    $allowed = ['cash_on_delivery','bkash','card'];
    if (!in_array($paymentOption, $allowed, true)) $paymentOption = 'cash_on_delivery';

    try {
        $pdo = db();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('INSERT INTO orders(user_id,total_amount,status) VALUES(?,?,?)');
        $stmt->execute([$_SESSION['user_id'], $total, 'pending']);
        $orderId = (int)$pdo->lastInsertId();

        $itemStmt = $pdo->prepare('INSERT INTO order_items(order_id,menu_id,name,price,quantity,subtotal) VALUES(?,?,?,?,?,?)');
        foreach ($items as $item) {
            $itemStmt->execute([$orderId,$item['menu_id'],$item['name'],$item['price'],$item['quantity'],$item['subtotal']]);
        }

        $status = $paymentOption === 'cash_on_delivery' ? 'pending' : 'paid';
        $ref = $paymentOption === 'cash_on_delivery' ? null : 'SIM-' . strtoupper(bin2hex(random_bytes(4)));
        $paidAt = $status === 'paid' ? date('Y-m-d H:i:s') : null;
        $stmt = $pdo->prepare('INSERT INTO payments(order_id,payment_option,amount,status,transaction_ref,paid_at) VALUES(?,?,?,?,?,?)');
        $stmt->execute([$orderId,$paymentOption,$total,$status,$ref,$paidAt]);

        $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id=?');
        $stmt->execute([$_SESSION['user_id']]);

        $pdo->commit();
        redirect('customer/order_success.php?id=' . $orderId);
    } catch (Throwable $e) {
        if (db()->inTransaction()) db()->rollBack();
        $error = 'Could not place the order. Please try again.';
    }
}

$page_title = 'Checkout';
require __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
<div class="col-lg-7">
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<h2>Checkout</h2>
<p class="text-secondary mb-4">Deliver to: <strong><?= e($customer['name'] ?? '') ?></strong>, <?= e($customer['address'] ?? 'No address saved') ?></p>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
<input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
<h5>Payment option</h5>
<div class="form-check"><input class="form-check-input" type="radio" name="payment_option" value="cash_on_delivery" id="cod" checked><label class="form-check-label" for="cod">Cash on Delivery</label></div>
<div class="form-check"><input class="form-check-input" type="radio" name="payment_option" value="bkash" id="bkash"><label class="form-check-label" for="bkash">bKash (simulated)</label></div>
<div class="form-check mb-4"><input class="form-check-input" type="radio" name="payment_option" value="card" id="card"><label class="form-check-label" for="card">Card (simulated)</label></div>
<button class="btn btn-dark btn-lg">Confirm Order — <?= money($total) ?></button>
</form>
</div></div></div>
<div class="col-lg-5">
<div class="card border-0 shadow-sm"><div class="card-body"><h5>Order Summary</h5>
<?php foreach ($items as $item): ?><div class="d-flex justify-content-between py-2"><span><?= e($item['name']) ?> × <?= $item['quantity'] ?></span><span><?= money((float)$item['subtotal']) ?></span></div><?php endforeach; ?>
<hr><div class="d-flex justify-content-between fw-bold"><span>Total</span><span><?= money($total) ?></span></div>
</div></div></div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
