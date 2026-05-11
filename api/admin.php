<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

require_admin();

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    flash('error', 'Invalid CSRF token.');
    header('Location: ' . url('admin/index.php'));
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'create_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $price = (float) ($_POST['price'] ?? 0);
    $categoryId = (int) ($_POST['category_id'] ?? 0);

    if ($name === '' || $description === '' || $price <= 0 || $categoryId < 1) {
        flash('error', 'Please fill all required fields.');
        header('Location: ' . url('admin/index.php'));
        exit;
    }
    $categoryCheck = db()->prepare('SELECT id FROM categories WHERE id = :id');
    $categoryCheck->execute(['id' => $categoryId]);
    if (!$categoryCheck->fetchColumn()) {
        flash('error', 'Selected category does not exist.');
        header('Location: ' . url('admin/index.php'));
        exit;
    }

    $imageUrl = 'https://images.unsplash.com/photo-1471943038886-87c772c31367?w=800';
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $extension = strtolower(pathinfo((string) $_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($extension, $allowed, true)) {
            $fileName = 'product_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
            $dest = __DIR__ . '/../uploads/' . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imageUrl = 'uploads/' . $fileName;
            }
        }
    }

    $stmt = db()->prepare('INSERT INTO products (category_id, name, description, price, image_url, stock, created_at) VALUES (:category_id, :name, :description, :price, :image_url, :stock, NOW())');
    $stmt->execute([
        'category_id' => $categoryId,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'image_url' => $imageUrl,
        'stock' => 100,
    ]);

    flash('success', 'Product created.');
    header('Location: ' . url('admin/index.php'));
    exit;
}

flash('error', 'Invalid admin action.');
header('Location: ' . url('admin/index.php'));
exit;
