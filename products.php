<?php require_once __DIR__ . '/includes/functions.php';
$page_title = 'Sneakers';
$q = trim($_GET['q'] ?? '');
$sql = 'SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id';
$args = [];
if ($q !== '') {
    $sql .= ' WHERE p.name LIKE ? OR c.name LIKE ?';
    $args = ['%' . $q . '%', '%' . $q . '%'];
}
$sql .= ' ORDER BY p.id DESC';
$s = $pdo->prepare($sql);
$s->execute($args);
$products = $s->fetchAll();
require 'includes/header.php';
require 'includes/navbar.php'; ?>
<section class="page-title">
    <p class="eyebrow">THE COLLECTION</p>
    <h1>All sneakers</h1>
    <p>Browse the collection and find a pair that fits your day.</p>
</section>
<form class="search" method="GET"><input name="q" value="<?= e($q) ?>" placeholder="Search sneakers or category"><button
        class="btn">Search</button></form>
<?php if (!$products): ?>
    <div class="empty">No sneakers found.</div><?php else: ?>
    <div class="grid"><?php foreach ($products as $p): ?>
            <article class="card"><a class="product-img"
                    href="<?= url('product_details.php?id=' . (int) $p['id']) ?>"><?php if ($img = product_image($p['image'])): ?><img
                            src="<?= e($img) ?>"
                            alt="<?= e($p['name']) ?>"><?php else: ?><b>SNEAKERS</b><?php endif; ?></a><small><?= e($p['category_name'] ?? 'Sneakers') ?></small>
                <h3><a href="<?= url('product_details.php?id=' . (int) $p['id']) ?>"><?= e($p['name']) ?></a></h3>
                <strong>₹<?= number_format($p['price'], 2) ?></strong>
            </article><?php endforeach; ?>
    </div><?php endif; ?>
<?php require 'includes/footer.php'; ?>