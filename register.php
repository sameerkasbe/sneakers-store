<?php require_once __DIR__ . '/includes/functions.php';
if (is_logged_in()) {
    header('Location: ' . url('index.php'));
    exit;
}
$page_title = 'Register';
$errors = [];
$name = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pw = $_POST['password'] ?? '';
    if ($name === '')
        $errors[] = 'Enter your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Enter a valid email.';
    if (strlen($pw) < 6)
        $errors[] = 'Password must be at least 6 characters.';
    if (!$errors) {
        $s = $pdo->prepare('SELECT id FROM users WHERE email=?');
        $s->execute([$email]);
        if ($s->fetch())
            $errors[] = 'Email already registered.';
        else {
            $s = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'customer')");
            $s->execute([$name, $email, md5($pw)]);
            set_flash('Registration successful. Please log in.');
            header('Location: ' . url('login.php'));
            exit;
        }
    }
}
require 'includes/header.php';
require 'includes/navbar.php'; ?>
<div class="form-card">
    <p class="eyebrow">CREATE AN ACCOUNT</p>
    <h1>Register</h1><?php foreach ($errors as $er): ?>
        <div class="alert error"><?= e($er) ?></div><?php endforeach; ?>
    <form method="POST"><label>Full name<input name="name" value="<?= e($name) ?>" required></label><label>Email<input
                type="email" name="email" value="<?= e($email) ?>" required></label><label>Password<input type="password"
                name="password" minlength="6" required></label><button class="btn full">Create account</button></form>
    <p>Already registered? <a href="<?= url('login.php') ?>">Login</a></p>
</div><?php require 'includes/footer.php'; ?>