<?php
require_once __DIR__ . '/../config/config.php';
if (is_logged_in()) redirect('index.php');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '') $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    if (!$errors) {
        try {
            $pdo = db();
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('INSERT INTO users(email,password) VALUES(?,?)');
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
            $userId = (int)$pdo->lastInsertId();
            $stmt = $pdo->prepare('INSERT INTO customers(user_id,name,phone,address) VALUES(?,?,?,?)');
            $stmt->execute([$userId, $name, $phone ?: null, $address ?: null]);
            $pdo->commit();
            flash('success', 'Registration successful. Please log in.');
            redirect('auth/login.php');
        } catch (PDOException $e) {
            if (db()->inTransaction()) db()->rollBack();
            $errors[] = $e->getCode() === '23000' ? 'That email is already registered.' : 'Registration failed.';
        }
    }
}

$page_title = 'Register';
require __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
<div class="col-md-7 col-lg-6">
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<h2>Create account</h2>
<?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
<form method="post">
<input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
<div class="mb-3"><label class="form-label">Full name</label><input class="form-control" name="name" required></div>
<div class="mb-3"><label class="form-label">G-Suite / Email</label><input class="form-control" type="email" name="email" required></div>
<div class="mb-3"><label class="form-label">Phone</label><input class="form-control" name="phone"></div>
<div class="mb-3"><label class="form-label">Delivery address</label><textarea class="form-control" name="address"></textarea></div>
<div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
<div class="mb-3"><label class="form-label">Confirm password</label><input class="form-control" type="password" name="confirm_password" required></div>
<button class="btn btn-dark w-100">Create Account</button>
</form>
</div></div></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
