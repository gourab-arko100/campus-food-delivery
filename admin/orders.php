<?php
require_once __DIR__ . '/../config/config.php';
require_admin();

$statuses=['pending','confirmed','preparing','ready','delivered','cancelled'];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $status=$_POST['status'] ?? 'pending';
    if (in_array($status,$statuses,true)) {
        $stmt=db()->prepare('UPDATE orders SET status=? WHERE id=?');
        $stmt->execute([$status,(int)$_POST['order_id']]);
        flash('success','Order status updated.');
    }
    redirect('admin/orders.php');
}
$orders=db()->query("SELECT o.*,u.email,c.name,p.payment_option,p.status payment_status FROM orders o JOIN users u ON u.id=o.user_id LEFT JOIN customers c ON c.user_id=u.id LEFT JOIN payments p ON p.order_id=o.id ORDER BY o.created_at DESC")->fetchAll();
$page_title='Manage Orders';
require __DIR__ . '/../includes/header.php';
?>
<h2>Manage Orders</h2>
<div class="table-responsive"><table class="table bg-white shadow-sm">
<thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
<tbody>
<?php foreach($orders as $o): ?><tr>
<td>#<?= $o['id'] ?></td><td><?= e($o['name'] ?: $o['email']) ?></td><td><?= money((float)$o['total_amount']) ?></td>
<td><?= e($o['payment_option'] ?? '-') ?> / <?= e($o['payment_status'] ?? '-') ?></td>
<td><form method="post" class="d-flex gap-2"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="order_id" value="<?= $o['id'] ?>"><select class="form-select form-select-sm" name="status"><?php foreach($statuses as $s): ?><option <?= $o['status']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?></select><button class="btn btn-sm btn-dark">Save</button></form></td>
<td><?= e($o['created_at']) ?></td>
</tr><?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
