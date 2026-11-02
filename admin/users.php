<?php require '_auth.php';
$users = $pdo->query("SELECT name,email,created_at FROM users WHERE role='customer' ORDER BY id DESC")->fetchAll();
$page_title = 'Customers';
require '../includes/header.php';
require '../includes/navbar.php'; ?>
<section class="page-title">
    <p class="eyebrow">CUSTOMER ACCOUNTS</p>
    <h1>Registered customers</h1>
</section>
<div class="table-wrap">
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Registered</th>
        </tr><?php foreach ($users as $u): ?>
            <tr>
                <td><?= e($u['name']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['created_at']) ?></td>
            </tr><?php endforeach; ?>
    </table>
</div><?php require '../includes/footer.php'; ?>