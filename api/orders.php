<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

require_auth();

if (($_GET['action'] ?? '') !== 'place' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
}

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    flash('error', 'Invalid CSRF token.');
    header('Location: /checkout.php');
    exit;
}

$pdo = db();
$pdo->beginTransaction();
try {
    $cartStmt = $pdo->prepare('SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = :uid');
    $cartStmt->execute(['uid' => current_user()['id']]);
    $items = $cartStmt->fetchAll();

    if (!$items) {
        throw new RuntimeException('Cart is empty.');
    }

    $total = 0.0;
    foreach ($items as $item) {
        $total += (float) $item['price'] * (int) $item['quantity'];
    }

    $order = $pdo->prepare('INSERT INTO orders (user_id, status, total_amount, shipping_address, payment_method, created_at) VALUES (:uid, :status, :total, :address, :payment, NOW())');
    $order->execute([
        'uid' => current_user()['id'],
        'status' => 'processing',
        'total' => $total,
        'address' => trim((string) ($_POST['address'] ?? '')),
        'payment' => trim((string) ($_POST['payment_method'] ?? 'cod')),
    ]);

    $orderId = (int) $pdo->lastInsertId();
    $itemInsert = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (:oid, :pid, :qty, :price)');
    foreach ($items as $item) {
        $itemInsert->execute([
            'oid' => $orderId,
            'pid' => $item['product_id'],
            'qty' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    $clear = $pdo->prepare('DELETE FROM cart WHERE user_id = :uid');
    $clear->execute(['uid' => current_user()['id']]);

    $pdo->commit();
    flash('success', 'Order placed successfully.');
    header('Location: /dashboard.php');
    exit;
} catch (Throwable $e) {
    $pdo->rollBack();
    flash('error', 'Could not place order: ' . $e->getMessage());
    header('Location: /checkout.php');
    exit;
}
