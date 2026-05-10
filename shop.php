<?php
$pageTitle = 'Shop';
require __DIR__ . '/includes/header.php';
$stmt = db()->query('SELECT p.id, p.name, p.price, p.image_url, c.name AS category FROM products p LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC LIMIT 30');
$products = $stmt->fetchAll();
?>
<section class="page-head"><h1>Shop</h1></section>
<section class="filters glass">
    <input id="productSearch" placeholder="Search honey...">
    <select id="sortSelect"><option value="default">Sort</option><option value="price_asc">Price ↑</option><option value="price_desc">Price ↓</option></select>
</section>
<section class="product-grid" id="productGrid">
    <?php foreach ($products as $product): ?>
        <article class="product-card" data-name="<?= e(strtolower($product['name'])) ?>" data-price="<?= e((string) $product['price']) ?>">
            <a href="/product.php?id=<?= (int) $product['id'] ?>">
                <img loading="lazy" src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>">
                <h3><?= e($product['name']) ?></h3>
                <p><?= e($product['category'] ?? 'Uncategorized') ?></p>
                <strong>$<?= e(number_format((float) $product['price'], 2)) ?></strong>
            </a>
            <button class="honey-btn add-cart" data-id="<?= (int) $product['id'] ?>">Add to Cart</button>
        </article>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
