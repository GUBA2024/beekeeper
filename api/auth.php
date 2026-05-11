<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

$action = $_GET['action'] ?? '';

if ($action === 'logout') {
    logout_user();
    flash('success', 'Logged out successfully.');
    header('Location: ' . url('auth.php?mode=login'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
}

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    json_response(['ok' => false, 'error' => 'Invalid CSRF token'], 422);
}

if ($action === 'register') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        flash('error', 'Please provide valid registration data.');
        header('Location: ' . url('auth.php?mode=register'));
        exit;
    }

    $result = register_user($name, strtolower($email), $password);
    flash($result['ok'] ? 'success' : 'error', $result['message']);
    header('Location: ' . url('auth.php?mode=' . ($result['ok'] ? 'login' : 'register')));
    exit;
}

if ($action === 'login') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    $result = login_user(strtolower($email), $password);
    flash($result['ok'] ? 'success' : 'error', $result['message']);
    header('Location: ' . ($result['ok'] ? url('dashboard.php') : url('auth.php?mode=login')));
    exit;
}

json_response(['ok' => false, 'error' => 'Invalid action'], 400);
