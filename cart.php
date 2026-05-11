<?php
$pageTitle = 'Cart';
require __DIR__ . '/includes/header.php';
require_auth();
$stmt = db()->prepare('SELECT c.id AS cart_id, c.quantity, p.id, p.name, p.price, p.image_url FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = :uid');
$stmt->execute(['uid' => current_user()['id']]);
$items = $stmt->fetchAll();
$total = 0.0;
?>
<section class="page-head"><h1>Your Cart</h1></section>
<section class="cart-list">
    <?php foreach ($items as $item): $line = (float)$item['price'] * (int)$item['quantity']; $total += $line; ?>
    <article class="cart-item glass">
        <img src="<?= asset_url($item['image_url']) ?>" alt="<?= e($item['name']) ?>">
        <div>
            <h3><?= e($item['name']) ?></h3>
            <p>$<?= e(number_format((float) $item['price'], 2)) ?></p>
            <form method="post" action="<?= url('api/cart.php?action=update') ?>">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="cart_id" value="<?= (int) $item['cart_id'] ?>">
                <input type="number" name="quantity" min="1" value="<?= (int) $item['quantity'] ?>">
                <button class="ghost-btn" type="submit">Update</button>
            </form>
            <form method="post" action="<?= url('api/cart.php?action=remove') ?>">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="cart_id" value="<?= (int) $item['cart_id'] ?>">
                <button class="ghost-btn" type="submit">Remove</button>
            </form>
        </div>
    </article>
    <?php endforeach; ?>
</section>
<section class="cart-total glass"><h2>Total: $<?= e(number_format($total, 2)) ?></h2><a class="honey-btn" href="<?= url('checkout.php') ?>">Proceed to Checkout</a></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
