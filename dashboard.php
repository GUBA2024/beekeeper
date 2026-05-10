<?php
$pageTitle = 'User Dashboard';
require __DIR__ . '/includes/header.php';
require_auth();
$user = current_user();
$orders = db()->prepare('SELECT id, total_amount, status, created_at FROM orders WHERE user_id = :uid ORDER BY created_at DESC');
$orders->execute(['uid' => $user['id']]);
?>
<section class="page-head"><h1>Welcome, <?= e($user['name']) ?></h1></section>
<section class="grid two-col">
    <article class="glass"><h2>Profile</h2><p><?= e($user['email']) ?></p></article>
    <article class="glass"><h2>Settings</h2><p>Manage account preferences and language/theme.</p></article>
</section>
<section class="glass">
    <h2>Orders</h2>
    <?php foreach ($orders as $order): ?>
        <p>#<?= (int) $order['id'] ?> — <?= e($order['status']) ?> — $<?= e(number_format((float) $order['total_amount'], 2)) ?></p>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
