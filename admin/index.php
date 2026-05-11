<?php
$pageTitle = 'Admin Dashboard';
require __DIR__ . '/../includes/header.php';
require_admin();
$stats = [
    'products' => (int) db()->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'orders' => (int) db()->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
    'customers' => (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn(),
];
$categories = db()->query('SELECT id, name FROM categories ORDER BY name ASC')->fetchAll();
?>
<section class="page-head"><h1>Admin Dashboard</h1></section>
<section class="stats">
    <div class="glass"><h3><?= $stats['products'] ?></h3><p>Products</p></div>
    <div class="glass"><h3><?= $stats['orders'] ?></h3><p>Orders</p></div>
    <div class="glass"><h3><?= $stats['customers'] ?></h3><p>Customers</p></div>
</section>
<section class="grid two-col">
    <article class="glass">
        <h2>Manage Products</h2>
        <form method="post" action="<?= url('api/admin.php?action=create_product') ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input name="name" placeholder="Name" required>
            <input name="price" type="number" step="0.01" min="0" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>"><?= e($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input name="image" type="file" accept="image/*">
            <button class="honey-btn" type="submit">Add Product</button>
        </form>
    </article>
    <article class="glass"><h2>Analytics</h2><p>Revenue and customer behavior insights.</p></article>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
