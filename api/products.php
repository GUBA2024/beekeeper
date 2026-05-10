<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = (int) ($_GET['id'] ?? 0);

if ($method === 'GET') {
    if ($id > 0) {
        $stmt = db()->prepare('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.id = :id');
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        json_response(['ok' => (bool) $product, 'data' => $product]);
    }

    $q = trim((string) ($_GET['q'] ?? ''));
    $sql = 'SELECT id, name, description, price, image_url FROM products';
    $params = [];
    if ($q !== '') {
        $sql .= ' WHERE name LIKE :q';
        $params['q'] = '%' . $q . '%';
    }
    $sql .= ' ORDER BY created_at DESC';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    json_response(['ok' => true, 'data' => $stmt->fetchAll()]);
}

json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
