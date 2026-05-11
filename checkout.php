<?php
$pageTitle = 'Checkout';
require __DIR__ . '/includes/header.php';
require_auth();
?>
<section class="page-head"><h1>Checkout</h1></section>
<section class="grid two-col">
    <form class="glass" method="post" action="<?= url('api/orders.php?action=place') ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Full Name<input name="full_name" required></label>
        <label>Email<input name="email" type="email" required value="<?= e(current_user()['email']) ?>"></label>
        <label>Address<textarea name="address" required></textarea></label>
        <label>Payment Method<select name="payment_method"><option value="cod">Cash on Delivery</option><option value="card">Card</option></select></label>
        <button class="honey-btn" type="submit">Place Order</button>
    </form>
    <article class="glass"><h2>Order Summary</h2><p>Your cart items and taxes are calculated securely.</p></article>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
