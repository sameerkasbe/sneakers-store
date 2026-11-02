<?php require '_auth.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$s = $pdo->prepare('SELECT * FROM products WHERE id=?');
$s->execute([$id ?: 0]);
$p = $s->fetch();
if (!$p) {
    set_flash('Product not found.', 'error');
    header('Location: ' . url('admin/manage_products.php'));
    exit;
}
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $cat = $_POST['category_id'] ?: null;
    $img = $p['image'];
    if ($name === '' || !is_numeric($price) || $price < 0 || filter_var($stock, FILTER_VALIDATE_INT) === false || $stock < 0)
        $err = 'Check name, price and stock.';
    if (!$err && !empty($_FILES['image']['name'])) {
        $mime = mime_content_type($_FILES['image']['tmp_name']);
        $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($types[$mime]) || $_FILES['image']['size'] > 3145728)
            $err = 'Use JPG, PNG or WEBP under 3 MB.';
        else {
            $fn = bin2hex(random_bytes(12)) . '.' . $types[$mime];
            if (move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/uploads/' . $fn)) {
                $img = 'assets/uploads/' . $fn;
                if ($p['image'] && is_file(__DIR__ . '/../' . $p['image']))
                    @unlink(__DIR__ . '/../' . $p['image']);
            } else
                $err = 'Image upload failed.';
        }
    }
    if (!$err) {
        $s = $pdo->prepare('UPDATE products SET category_id=?,name=?,description=?,price=?,image=?,stock=? WHERE id=?');
        $s->execute([$cat, $name, $desc, $price, $img, $stock, $id]);
        set_flash('Product updated.');
        header('Location: ' . url('admin/manage_products.php'));
        exit;
    }
    $p = array_merge($p, ['name' => $name, 'description' => $desc, 'price' => $price, 'stock' => $stock, 'category_id' => $cat, 'image' => $img]);
}
$page_title = 'Edit product';
require '../includes/header.php';
require '../includes/navbar.php'; ?>
<div class="form-card wide">
    <p class="eyebrow">STORE INVENTORY</p>
    <h1>Edit sneaker</h1><?php if ($err): ?>
        <div class="alert error"><?= e($err) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data"><label>Name<input name="name" value="<?= e($p['name']) ?>"
                required></label><label>Category<select name="category_id">
                <option value="">No category</option><?php foreach ($cats as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $p['category_id'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select></label><label>Description<textarea name="description"><?= e($p['description']) ?></textarea></label>
        <div class="two"><label>Price ₹<input type="number" step=".01" min="0" name="price" value="<?= e($p['price']) ?>"
                    required></label><label>Stock<input type="number" min="0" name="stock" value="<?= e($p['stock']) ?>"
                    required></label></div><label>Replace image<input type="file" name="image"
                accept=".jpg,.jpeg,.png,.webp"></label><button class="btn full">Update product</button>
    </form>
</div><?php require '../includes/footer.php'; ?>