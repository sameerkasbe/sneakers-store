<?php require_once __DIR__ . '/includes/functions.php';
if (is_logged_in()) {
    header('Location: ' . (is_admin() ? url('admin/dashboard.php') : url('index.php')));
    exit;
}
$page_title = 'Login';
$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pw = $_POST['password'] ?? '';
    $s = $pdo->prepare('SELECT id,name,email,password,role FROM users WHERE email=? LIMIT 1');
    $s->execute([$email]);
    $u = $s->fetch();
    if ($u && hash_equals($u['password'], md5($pw))) {
        session_regenerate_id(true);
        unset($u['password']);
        $_SESSION['user'] = $u;
        set_flash('Welcome back, ' . $u['name'] . '!');
        header('Location: ' . ($u['role'] === 'admin' ? url('admin/dashboard.php') : url('index.php')));
        exit;
    } else {
        $error = 'Incorrect email or password.';
    }
}
require 'includes/header.php';
require 'includes/navbar.php'; ?>
<div class="form-card">
    <p class="eyebrow">WELCOME BACK</p>
    <h1>Login</h1><?php if ($error): ?>
        <div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="POST"><label>Email<input type="email" name="email" value="<?= e($email) ?>"
                required></label><label>Password<input type="password" name="password" required></label><button
            class="btn full">Login</button></form>
    <p>New here? <a href="<?= url('register.php') ?>">Register</a></p>
</div><?php require 'includes/footer.php'; ?>