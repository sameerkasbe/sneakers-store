<?php require '_auth.php';
$states = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = $_POST['status'] ?? '';
    if (in_array($st, $states, true)) {
        $s = $pdo->prepare('UPDATE orders SET status=? WHERE id=?');
        $s->execute([$st, (int) $_POST['order_id']]);
        set_flash('Order status updated.');
    }
    header('Location: ' . url('admin/orders.php'));
    exit;
}
$orders = $pdo->query('SELECT o.*,u.name,u.email FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.id DESC')->fetchAll();
$page_title = 'Orders';
require '../includes/header.php';
require '../includes/navbar.php'; ?>
<section class="page-title">
    <p class="eyebrow">STORE ACTIVITY</p>
    <h1>Customer orders</h1>
</section>
<div class="table-wrap">
    <table>
        <tr>
            <th>Order</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
        </tr><?php foreach ($orders as $o): ?>
            <tr>
                <td>#<?= $o['id'] ?></td>
                <td><?= e($o['name']) ?><br><?= e($o['email']) ?></td>
                <td><?= e($o['created_at']) ?></td>
                <td>₹<?= number_format($o['total'], 2) ?></td>
                <td>
                    <form class="status-form" method="POST"><input type="hidden" name="order_id"
                            value="<?= $o['id'] ?>"><select name="status"><?php foreach ($states as $st): ?>
                                <option <?= $o['status'] === $st ? 'selected' : '' ?>><?= $st ?></option><?php endforeach; ?>
                        </select><button class="btn small">Save</button></form>
                </td>
            </tr><?php endforeach; ?>
    </table>
</div><?php require '../includes/footer.php'; ?>