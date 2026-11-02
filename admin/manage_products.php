<?php require '_auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $s = $pdo->prepare('SELECT image FROM products WHERE id=?');
    $s->execute([$id]);
    $img = $s->fetchColumn();
    $s = $pdo->prepare('DELETE FROM products WHERE id=?');
    $s->execute([$id]);
    if ($img && is_file(__DIR__ . '/../' . $img))
        @unlink(__DIR__ . '/../' . $img);
    set_flash('Product deleted.');
    header('Location: ' . url('admin/manage_products.php'));
    exit;
}
$products = $pdo->query('SELECT p.*,c.name category FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC')->fetchAll();
$page_title = 'Manage products';
require '../includes/header.php';
require '../includes/navbar.php'; ?>
<section class="page-title row">
    <div>
        <p class="eyebrow">STORE INVENTORY</p>
        <h1>Manage products</h1>
    </div><a class="btn" href="<?= url('admin/add_product.php') ?>">＋ Add product</a>
</section>
<div class="table-wrap">
    <table>
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr><?php foreach ($products as $p): ?>
            <tr>
                <td><?= e($p['name']) ?></td>
                <td><?= e($p['category'] ?? '—') ?></td>
                <td>₹<?= number_format($p['price'], 2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td><a href="<?= url('admin/edit_product.php?id=' . $p['id']) ?>">Edit</a>
                    <form class="inline" method="POST" onsubmit="return confirm('Delete this product?')"><input
                            type="hidden" name="delete_id" value="<?= $p['id'] ?>"><button class="link danger">Delete</button>
                    </form>
                </td>
            </tr><?php endforeach; ?>
    </table>
</div><?php require '../includes/footer.php'; ?>