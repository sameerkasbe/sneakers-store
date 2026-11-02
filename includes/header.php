<?php require_once __DIR__ . '/functions.php';
$page_title = $page_title ?? 'Sneaker Store'; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($page_title) ?> | Sneaker Store</title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>

<body>