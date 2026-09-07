<?php
require_once __DIR__ . '/../config/config.php';
if (is_logged_in()) redirect('index.php');

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        redirect('index.php');
    }
    $error = 'Invalid email or password.';
}
$page_title = 'Login';
require __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center"><div class="col-md-5">
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<h2>Login</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
<input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
<div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
<div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
<button class="btn btn-dark w-100">Login</button>
</form>
<p class="mt-3 mb-0 text-center">New here? <a href="<?= url('auth/register.php') ?>">Create an account</a></p>
</div></div></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
