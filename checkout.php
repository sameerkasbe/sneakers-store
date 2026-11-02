<?php require_once __DIR__ . '/includes/functions.php';
require_login();
$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    set_flash('Your cart is empty.', 'error');
    header('Location: ' . url('cart.php'));
    exit;
}
$ids = array_map('intval', array_keys($cart));
$marks = implode(',', array_fill(0, count($ids), '?'));
$s = $pdo->prepare("SELECT * FROM products WHERE id IN ($marks)");
$s->execute($ids);
$products = $s->fetchAll();
$total = 0;
foreach ($products as $p) {
    $q = (int) $cart[$p['id']];
    if ($q > (int) $p['stock']) {
        set_flash('Cart quantity exceeds available stock.', 'error');
        header('Location: ' . url('cart.php'));
        exit;
    }
    $total += $p['price'] * $q;
}
if (count($products) !== count($cart)) {
    set_flash('A product is unavailable.', 'error');
    header('Location: ' . url('cart.php'));
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST')
    try {
        $pdo->beginTransaction();
        $s = $pdo->prepare("INSERT INTO orders(user_id,total) VALUES(?,?)");
        $s->execute([$_SESSION['user']['id'], $total]);
        $oid = $pdo->lastInsertId();
        $it = $pdo->prepare('INSERT INTO order_items(order_id,product_id,product_name,price,quantity) VALUES(?,?,?,?,?)');
        $up = $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=? AND stock>=?');
        foreach ($products as $p) {
            $q = (int) $cart[$p['id']];
            $up->execute([$q, $p['id'], $q]);
            if (!$up->rowCount())
                throw new Exception('Stock changed. Please try again.');
            $it->execute([$oid, $p['id'], $p['name'], $p['price'], $q]);
        }
        $pdo->commit();
        unset($_SESSION['cart']);
        set_flash('Order placed successfully. Order #' . $oid);
        header('Location: ' . url('my_orders.php'));
        exit;
    } catch (Throwable $e) {
        if ($pdo->inTransaction())
            $pdo->rollBack();
        set_flash($e->getMessage(), 'error');
        header('Location: ' . url('cart.php'));
        exit;
    }
$page_title = 'Checkout';
require 'includes/header.php';
require 'includes/navbar.php'; ?>
<div class="form-card wide">
    <p class="eyebrow">FINAL STEP</p>
    <h1>Checkout</h1><?php foreach ($products as $p): ?>
        <p class="between"><?= e($p['name']) ?> × <?= $cart[$p['id']] ?>
            <b>₹<?= number_format($p['price'] * $cart[$p['id']], 2) ?></b></p><?php endforeach; ?>
    <h2>Total ₹<?= number_format($total, 2) ?></h2>
    <form method="POST"><button class="btn full">Place order</button></form><small>Demo checkout only. No online payment
        is processed.</small>
</div><?php require 'includes/footer.php'; ?>