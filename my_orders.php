<?php require_once __DIR__ . '/includes/functions.php';
require_login();
$s = $pdo->prepare('SELECT * FROM orders WHERE user_id=? ORDER BY id DESC');
$s->execute([$_SESSION['user']['id']]);
$orders = $s->fetchAll();
$page_title = 'My orders';
require 'includes/header.php';
require 'includes/navbar.php'; ?>
<section class="page-title">
    <p class="eyebrow">ORDER HISTORY</p>
    <h1>My orders</h1>
</section><?php if (!$orders): ?>
    <div class="empty">No orders yet.</div><?php else: ?>
    <div class="table-wrap">
        <table>
            <tr>
                <th>Order</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
            </tr><?php foreach ($orders as $o): ?>
                <tr>
                    <td>#<?= $o['id'] ?></td>
                    <td><?= e($o['created_at']) ?></td>
                    <td>₹<?= number_format($o['total'], 2) ?></td>
                    <td><?= e($o['status']) ?></td>
                </tr><?php endforeach; ?>
        </table>
    </div><?php endif; ?><?php require 'includes/footer.php'; ?>