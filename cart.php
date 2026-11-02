<?php require_once __DIR__ . '/includes/functions.php';
if (!isset($_SESSION['cart']))
    $_SESSION['cart'] = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $id = (int) ($_POST['product_id'] ?? 0);
    $qty = max(1, (int) ($_POST['quantity'] ?? 1));
    $s = $pdo->prepare('SELECT stock FROM products WHERE id=?');
    $s->execute([$id]);
    $stock = $s->fetchColumn();
    if ($stock !== false && (int) $stock > 0) {
        $_SESSION['cart'][$id] = min((int) $stock, ($_SESSION['cart'][$id] ?? 0) + $qty);
        set_flash('Added to cart.');
    } else
        set_flash('Product unavailable.', 'error');
    header('Location: ' . url('cart.php'));
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] ?? [] as $id => $q) {
        $id = (int) $id;
        $q = (int) $q;
        if ($q < 1)
            unset($_SESSION['cart'][$id]);
        else {
            $s = $pdo->prepare('SELECT stock FROM products WHERE id=?');
            $s->execute([$id]);
            $st = $s->fetchColumn();
            if ($st === false)
                unset($_SESSION['cart'][$id]);
            else
                $_SESSION['cart'][$id] = min($q, (int) $st);
        }
    }
    header('Location: ' . url('cart.php'));
    exit;
}
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int) $_GET['remove']]);
    header('Location: ' . url('cart.php'));
    exit;
}
$items = [];
$total = 0;
if ($_SESSION['cart']) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $s = $pdo->prepare("SELECT * FROM products WHERE id IN ($marks)");
    $s->execute($ids);
    foreach ($s->fetchAll() as $p) {
        $p['qty'] = $_SESSION['cart'][$p['id']];
        $p['sub'] = $p['qty'] * $p['price'];
        $total += $p['sub'];
        $items[] = $p;
    }
}
$page_title = 'Cart';
require 'includes/header.php';
require 'includes/navbar.php'; ?>
<section class="page-title">
    <p class="eyebrow">YOUR SELECTION</p>
    <h1>Shopping cart</h1>
</section><?php if (!$items): ?>
    <div class="empty">Your cart is empty. <a href="<?= url('products.php') ?>">Browse sneakers</a></div><?php else: ?>
    <form method="POST">
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr><?php foreach ($items as $p): ?>
                    <tr>
                        <td><?= e($p['name']) ?></td>
                        <td>₹<?= number_format($p['price'], 2) ?></td>
                        <td><input class="qty" type="number" min="0" name="qty[<?= $p['id'] ?>]" value="<?= $p['qty'] ?>"></td>
                        <td>₹<?= number_format($p['sub'], 2) ?></td>
                        <td><a href="<?= url('cart.php?remove=' . $p['id']) ?>">Remove</a></td>
                    </tr><?php endforeach; ?>
            </table>
        </div>
        <div class="cart-foot"><button class="btn outline" name="update_cart">Update cart</button><strong>Total:
                ₹<?= number_format($total, 2) ?> <a class="btn" href="<?= url('checkout.php') ?>">Checkout</a></strong></div>
    </form><?php endif; ?><?php require 'includes/footer.php'; ?>