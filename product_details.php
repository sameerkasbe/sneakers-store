<?php require_once __DIR__ . '/includes/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$s = $pdo->prepare('SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id=?');
$s->execute([$id ?: 0]);
$p = $s->fetch();
if (!$p) {
    http_response_code(404);
    $page_title = 'Not found';
    require 'includes/header.php';
    require 'includes/navbar.php';
    echo '<div class="empty">Product not found. <a href="' . e(url('products.php')) . '">Browse sneakers</a></div>';
    require 'includes/footer.php';
    exit;
}
$page_title = $p['name'];
require 'includes/header.php';
require 'includes/navbar.php'; ?>
<div class="detail">
    <div class="detail-img"><?php if ($img = product_image($p['image'])): ?><img src="<?= e($img) ?>"
                alt="<?= e($p['name']) ?>"><?php else: ?><b>SNEAKERS</b><?php endif; ?></div>
    <div>
        <p class="eyebrow"><?= e($p['category_name'] ?? 'SNEAKERS') ?></p>
        <h1><?= e($p['name']) ?></h1>
        <h2>₹<?= number_format($p['price'], 2) ?></h2>
        <p><?= nl2br(e($p['description'])) ?></p>
        <p><?= ((int) $p['stock'] > 0) ? (int) $p['stock'] . ' available' : 'Out of stock' ?></p><?php if ($p['stock'] > 0): ?>
            <form method="POST" action="<?= url('cart.php') ?>"><input type="hidden" name="product_id"
                    value="<?= $p['id'] ?>"><label>Quantity <input type="number" name="quantity" value="1" min="1"
                        max="<?= $p['stock'] ?>"></label> <button class="btn" name="add_to_cart">Add to cart</button></form>
        <?php endif; ?>
    </div>
</div>
<?php require 'includes/footer.php'; ?>