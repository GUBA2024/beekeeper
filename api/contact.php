<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
}

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    flash('error', 'Security validation failed.');
    header('Location: ' . url('index.php'));
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$message = trim((string) ($_POST['message'] ?? 'Newsletter subscription'));

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('error', 'Invalid contact details.');
    header('Location: ' . url('index.php'));
    exit;
}

$stmt = db()->prepare('INSERT INTO notifications (user_id, source, title, body, created_at) VALUES (NULL, :source, :title, :body, NOW())');
$stmt->execute(['source' => 'contact', 'title' => 'Contact: ' . $name, 'body' => $email . ' - ' . $message]);

flash('success', 'Message received. Our team will contact you soon.');
header('Location: ' . url('index.php'));
exit;
