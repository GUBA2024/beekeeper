<?php
$pageTitle = 'Login / Register';
require __DIR__ . '/includes/header.php';
$mode = $_GET['mode'] ?? 'login';
?>
<section class="auth-wrap glass">
    <div class="auth-tabs">
        <a href="<?= url('auth.php?mode=login') ?>" class="<?= $mode === 'login' ? 'active' : '' ?>">Login</a>
        <a href="<?= url('auth.php?mode=register') ?>" class="<?= $mode === 'register' ? 'active' : '' ?>">Register</a>
    </div>
    <?php if ($mode === 'register'): ?>
    <form method="post" action="<?= url('api/auth.php?action=register') ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input name="name" placeholder="Name" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required minlength="8">
        <button class="honey-btn" type="submit">Create Account</button>
    </form>
    <?php else: ?>
    <form method="post" action="<?= url('api/auth.php?action=login') ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input name="email" type="email" placeholder="Email" required>
        <input id="passwordInput" name="password" type="password" placeholder="Password" required>
        <button type="button" class="ghost-btn" id="togglePassword">Show/Hide</button>
        <button class="honey-btn" type="submit">Login</button>
    </form>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
