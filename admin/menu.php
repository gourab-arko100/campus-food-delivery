<?php
require_once __DIR__ . '/../config/config.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $stmt=db()->prepare('DELETE FROM menu WHERE id=?');
        $stmt->execute([(int)$_POST['id']]);
        flash('success','Menu item deleted.');
    } elseif ($action === 'save') {
        $id=(int)($_POST['id'] ?? 0);
        $name=trim($_POST['name'] ?? '');
        $description=trim($_POST['description'] ?? '');
        $price=(float)($_POST['price'] ?? 0);
        $available=isset($_POST['available']) ? 1 : 0;
        if ($id) {
            $stmt=db()->prepare('UPDATE menu SET name=?,description=?,price=?,available=? WHERE id=?');
            $stmt->execute([$name,$description,$price,$available,$id]);
        } else {
            $stmt=db()->prepare('INSERT INTO menu(name,description,price,available) VALUES(?,?,?,?)');
            $stmt->execute([$name,$description,$price,$available]);
        }
        flash('success','Menu saved.');
    }
    redirect('admin/menu.php');
}
$edit=null;
if (isset($_GET['edit'])) {
    $stmt=db()->prepare('SELECT * FROM menu WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit=$stmt->fetch();
}
$menu=db()->query('SELECT * FROM menu ORDER BY id DESC')->fetchAll();
$page_title='Manage Menu';
require __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
<div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body">
<h4><?= $edit ? 'Edit Item' : 'Add Item' ?></h4>
<form method="post">
<input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
<div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= e($edit['name'] ?? '') ?>" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description"><?= e($edit['description'] ?? '') ?></textarea></div>
<div class="mb-3"><label class="form-label">Price</label><input class="form-control" type="number" step="0.01" name="price" value="<?= e((string)($edit['price'] ?? '0')) ?>" required></div>
<div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="available" id="available" <?= (!$edit || $edit['available']) ? 'checked' : '' ?>><label class="form-check-label" for="available">Available</label></div>
<button class="btn btn-dark">Save</button>
<?php if ($edit): ?><a class="btn btn-outline-secondary" href="<?= url('admin/menu.php') ?>">Cancel</a><?php endif; ?>
</form>
</div></div></div>
<div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="card-body"><h4>Menu</h4>
<div class="table-responsive"><table class="table"><thead><tr><th>Name</th><th>Price</th><th>Available</th><th></th></tr></thead><tbody>
<?php foreach($menu as $m): ?><tr><td><?= e($m['name']) ?></td><td><?= money((float)$m['price']) ?></td><td><?= $m['available']?'Yes':'No' ?></td><td><a class="btn btn-sm btn-outline-dark" href="?edit=<?= $m['id'] ?>">Edit</a> <form class="d-inline" method="post"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $m['id'] ?>"><button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this item?')">Delete</button></form></td></tr><?php endforeach; ?>
</tbody></table></div>
</div></div></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
