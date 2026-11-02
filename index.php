<?php require_once __DIR__ . '/includes/functions.php';
$page_title = 'Home';
$products = $pdo->query('SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC LIMIT 4')->fetchAll();
require 'includes/header.php';
require 'includes/navbar.php'; ?>
<section class="hero">
    <div>
        <p class="eyebrow">THE EVERYDAY SNEAKER STORE</p>
        <h1>Find your<br><span>next favourite.</span></h1>
        <p>Simple, comfortable sneakers for every day. Explore the latest styles in our collection.</p><a class="btn"
            href="<?= url('products.php') ?>">Shop sneakers</a>
    </div>
    <div class="hero-art"><b>S</b>
        <p>STEP INTO YOUR STYLE</p>
    </div>
</section>
<div class="section-head">
    <div>
        <p class="eyebrow">OUR COLLECTION</p>
        <h2>Featured sneakers</h2>
    </div><a href="<?= url('products.php') ?>">View all →</a>
</div>
<div class="grid">
    <?php foreach ($products as $p): ?>
        <article class="card"><a class="product-img"
                href="<?= url('product_details.php?id=' . (int) $p['id']) ?>"><?php if ($img = product_image($p['image'])): ?><img
                        src="<?= e($img) ?>"
                        alt="<?= e($p['name']) ?>"><?php else: ?><b>SNEAKERS</b><?php endif; ?></a><small><?= e($p['category_name'] ?? 'Sneakers') ?></small>
            <h3><a href="<?= url('product_details.php?id=' . (int) $p['id']) ?>"><?= e($p['name']) ?></a></h3>
            <strong>₹<?= number_format($p['price'], 2) ?></strong>
        </article><?php endforeach; ?>
</div>
<?php require 'includes/footer.php'; ?>