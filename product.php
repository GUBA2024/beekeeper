<?php
$pageTitle = 'Product Details';
require __DIR__ . '/includes/header.php';
$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.id = :id');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    echo '<section class="page-head"><h1>Product not found</h1></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$reviews = db()->prepare('SELECT r.rating, r.comment, u.name FROM reviews r JOIN users u ON u.id = r.user_id WHERE r.product_id = :id ORDER BY r.created_at DESC LIMIT 6');
$reviews->execute(['id' => $id]);
?>
<section class="product-details grid two-col">
    <article>
        <img src="<?= asset_url($product['image_url']) ?>" alt="<?= e($product['name']) ?>" class="zoomable">
    </article>
    <article>
        <h1><?= e($product['name']) ?></h1>
        <p><?= e($product['description']) ?></p>
        <strong>$<?= e(number_format((float) $product['price'], 2)) ?></strong>
        <div class="qty-wrap"><input type="number" id="qty" min="1" value="1"></div>
        <button class="honey-btn add-cart" data-id="<?= (int) $product['id'] ?>">Add to Cart</button>
    </article>
</section>
<section class="reviews">
    <h2>Reviews</h2>
    <?php foreach ($reviews as $review): ?>
        <article class="glass"><strong><?= e($review['name']) ?></strong> (<?= (int) $review['rating'] ?>/5)<p><?= e($review['comment']) ?></p></article>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
