<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$action = $_GET['action'] ?? '';

// For the AJAX "add" action, return JSON so the browser can act on it.
if ($action === 'add') {
    if (!is_authenticated()) {
        json_response(['ok' => false, 'redirect' => url('auth.php?mode=login')], 401);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_validate($_POST['csrf_token'] ?? null)) {
        json_response(['ok' => false, 'error' => 'Invalid request.'], 422);
    }

    $uid = current_user()['id'];
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

    $stmt = db()->prepare('INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (:uid, :pid, :qty, NOW()) AS new_row ON DUPLICATE KEY UPDATE quantity = cart.quantity + new_row.quantity');
    $stmt->execute(['uid' => $uid, 'pid' => $productId, 'qty' => $quantity]);
    json_response(['ok' => true]);
}

// Remaining actions (update, remove) use normal form POST + redirect.
require_auth();
$uid = current_user()['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_validate($_POST['csrf_token'] ?? null)) {
    flash('error', 'Invalid request.');
    header('Location: ' . url('cart.php'));
    exit;
}

if ($action === 'update') {
    $cartId = (int) ($_POST['cart_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    $stmt = db()->prepare('UPDATE cart SET quantity = :qty WHERE id = :id AND user_id = :uid');
    $stmt->execute(['qty' => $quantity, 'id' => $cartId, 'uid' => $uid]);
    flash('success', 'Cart updated.');
    header('Location: ' . url('cart.php'));
    exit;
}

if ($action === 'remove') {
    $cartId = (int) ($_POST['cart_id'] ?? 0);
    $stmt = db()->prepare('DELETE FROM cart WHERE id = :id AND user_id = :uid');
    $stmt->execute(['id' => $cartId, 'uid' => $uid]);
    flash('success', 'Item removed.');
    header('Location: ' . url('cart.php'));
    exit;
}

flash('error', 'Unknown cart action.');
header('Location: ' . url('cart.php'));
exit;
