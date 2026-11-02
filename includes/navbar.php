<header class="site-header">
    <div class="container nav"><a class="brand" href="<?= url('index.php') ?>">sneaker<span>store.</span></a>
        <nav><a href="<?= url('index.php') ?>">Home</a><a href="<?= url('products.php') ?>">Sneakers</a><a
                href="<?= url('about.php') ?>">About</a><a href="<?= url('cart.php') ?>">Cart
                (<?= cart_count() ?>)</a><?php if (is_admin()): ?><a
                    href="<?= url('admin/dashboard.php') ?>">Admin</a><?php endif; ?></nav>
        <div><?php if (is_logged_in()): ?><span class="welcome">Hi, <?= e($_SESSION['user']['name']) ?></span> <a
                    href="<?= url('logout.php') ?>">Logout</a><?php else: ?><a href="<?= url('login.php') ?>">Login</a> <a
                    class="btn small" href="<?= url('register.php') ?>">Register</a><?php endif; ?></div>
    </div>
</header>
<main class="container main"><?= flash_html() ?>