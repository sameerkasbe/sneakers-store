<?php require '_auth.php';
$page_title = 'Admin dashboard';
$pc = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$uc = $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
$oc = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$rev = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status!='Cancelled'")->fetchColumn();
require '../includes/header.php';
require '../includes/navbar.php'; ?>
<section class="page-title">
    <p class="eyebrow">STORE MANAGEMENT</p>
    <h1>Admin dashboard</h1>
    <p>Manage products and store activity.</p>
</section>
<div class="stats">
    <div>Products<strong><?= $pc ?></strong></div>
    <div>Customers<strong><?= $uc ?></strong></div>
    <div>Orders<strong><?= $oc ?></strong></div>
    <div>Order value<strong>₹<?= number_format($rev, 2) ?></strong></div>
</div>
<div class="admin-links"><a href="<?= url('admin/add_product.php') ?>">＋ Add product</a><a
        href="<?= url('admin/manage_products.php') ?>">Manage products →</a><a href="<?= url('admin/orders.php') ?>">View
        orders →</a><a href="<?= url('admin/users.php') ?>">View customers →</a></div>
<?php require '../includes/footer.php'; ?>