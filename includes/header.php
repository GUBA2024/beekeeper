<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';
$config = require __DIR__ . '/config.php';
$user = current_user();
$flashes = consume_flash();
$pageTitle = isset($pageTitle) ? $pageTitle . ' | ' . $config['app_name'] : $config['app_name'];
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="Golden Hive luxury natural honey e-commerce store.">
    <meta name="keywords" content="honey, luxury honey, natural honey, golden hive">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div id="loader"><div class="honey-fill"></div><span>Golden Hive</span></div>
<div class="honey-cursor"></div>
<nav class="top-nav glass">
    <a href="/index.php" class="brand"><i class="fa-solid fa-hexagon"></i> Golden Hive</a>
    <button class="menu-toggle" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
    <ul class="nav-links">
        <li><a href="/index.php" data-i18n="home">Home</a></li>
        <li><a href="/shop.php" data-i18n="shop">Shop</a></li>
        <li><a href="/cart.php" data-i18n="cart">Cart</a></li>
        <li><a href="/checkout.php" data-i18n="checkout">Checkout</a></li>
        <?php if ($user): ?>
            <li><a href="/dashboard.php" data-i18n="dashboard">Dashboard</a></li>
            <?php if (!empty($user['is_admin'])): ?><li><a href="/admin/index.php">Admin</a></li><?php endif; ?>
            <li><a href="/api/auth.php?action=logout">Logout</a></li>
        <?php else: ?>
            <li><a href="/auth.php" data-i18n="login">Login</a></li>
        <?php endif; ?>
    </ul>
    <div class="nav-actions">
        <button id="langToggle" class="ghost-btn">AR</button>
        <button id="themeToggle" class="ghost-btn"><i class="fa-solid fa-moon"></i></button>
    </div>
</nav>
<?php if ($flashes): ?>
<div class="toast-wrap">
    <?php foreach ($flashes as $flash): ?>
        <div class="toast toast-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<main>
