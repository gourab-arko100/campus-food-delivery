<?php
require_once __DIR__ . '/../config/config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $menuId = (int)$_POST['menu_id'];
        $stmt = db()->prepare('INSERT INTO cart(user_id,menu_id,quantity) VALUES(?,?,1) ON DUPLICATE KEY UPDATE quantity=quantity+1');
        $stmt->execute([$_SESSION['user_id'], $menuId]);
        flash('success', 'Item added to cart.');
        redirect('customer/cart.php');
    }

    if ($action === 'update') {
        $cartId = (int)$_POST['cart_id'];
        $quantity = max(0, (int)$_POST['quantity']);
        if ($quantity === 0) {
            $stmt = db()->prepare('DELETE FROM cart WHERE id=? AND user_id=?');
            $stmt->execute([$cartId, $_SESSION['user_id']]);
        } else {
            $stmt = db()->prepare('UPDATE cart SET quantity=? WHERE id=? AND user_id=?');
            $stmt->execute([$quantity, $cartId, $_SESSION['user_id']]);
        }
        redirect('customer/cart.php');
    }

    if ($action === 'remove') {
        $stmt = db()->prepare('DELETE FROM cart WHERE id=? AND user_id=?');
        $stmt->execute([(int)$_POST['cart_id'], $_SESSION['user_id']]);
        redirect('customer/cart.php');
    }
}

$stmt = db()->prepare('SELECT c.id,c.quantity,m.name,m.price,m.image,m.description,(c.quantity*m.price) subtotal FROM cart c JOIN menu m ON m.id=c.menu_id WHERE c.user_id=? ORDER BY c.id DESC');
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();
$total = array_sum(array_map(fn($x)=>(float)$x['subtotal'], $items));

$page_title = 'Your Cart';
require __DIR__ . '/../includes/header.php';
?>
<h2>Your Cart</h2>
<?php if (!$items): ?>
<div class="alert alert-info">Your cart is empty. <a href="<?= url('index.php') ?>">Browse the menu</a>.</div>
<?php else: ?>
<div class="table-responsive"><table class="table bg-white shadow-sm">
<thead><tr><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead>
<tbody>
<?php foreach ($items as $item): ?>
<tr>
<td><?= e($item['name']) ?></td><td><?= money((float)$item['price']) ?></td>
<td><form class="d-flex gap-2" method="post"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="action" value="update"><input type="hidden" name="cart_id" value="<?= $item['id'] ?>"><input class="form-control" style="width:80px" type="number" min="1" max="99" name="quantity" value="<?= $item['quantity'] ?>"><button class="btn btn-sm btn-outline-dark">Update</button></form></td>
<td><?= money((float)$item['subtotal']) ?></td>
<td><form method="post"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="action" value="remove"><input type="hidden" name="cart_id" value="<?= $item['id'] ?>"><button class="btn btn-sm btn-outline-danger">Remove</button></form></td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<div class="d-flex justify-content-between align-items-center">
<h4>Total: <?= money($total) ?></h4>
<a class="btn btn-dark" href="<?= url('customer/checkout.php') ?>">Proceed to Checkout</a>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
