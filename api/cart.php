<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

require_auth();
$action = $_GET['action'] ?? '';
$uid = current_user()['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_validate($_POST['csrf_token'] ?? null)) {
    flash('error', 'Invalid request.');
    header('Location: /cart.php');
    exit;
}

if ($action === 'add') {
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

    $stmt = db()->prepare('INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (:uid, :pid, :qty, NOW()) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)');
    $stmt->execute(['uid' => $uid, 'pid' => $productId, 'qty' => $quantity]);
    flash('success', 'Added to cart.');
    header('Location: /cart.php');
    exit;
}

if ($action === 'update') {
    $cartId = (int) ($_POST['cart_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    $stmt = db()->prepare('UPDATE cart SET quantity = :qty WHERE id = :id AND user_id = :uid');
    $stmt->execute(['qty' => $quantity, 'id' => $cartId, 'uid' => $uid]);
    flash('success', 'Cart updated.');
    header('Location: /cart.php');
    exit;
}

if ($action === 'remove') {
    $cartId = (int) ($_POST['cart_id'] ?? 0);
    $stmt = db()->prepare('DELETE FROM cart WHERE id = :id AND user_id = :uid');
    $stmt->execute(['id' => $cartId, 'uid' => $uid]);
    flash('success', 'Item removed.');
    header('Location: /cart.php');
    exit;
}

flash('error', 'Unknown cart action.');
header('Location: /cart.php');
