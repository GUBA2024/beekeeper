<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function register_user(string $name, string $email, string $password): array
{
    $stmt = db()->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        return ['ok' => false, 'message' => 'Email already exists.'];
    }

    $insert = db()->prepare('INSERT INTO users (name, email, password_hash, created_at) VALUES (:name, :email, :password_hash, NOW())');
    $insert->execute([
        'name' => $name,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    return ['ok' => true, 'message' => 'Account created successfully.'];
}

function login_user(string $email, string $password): array
{
    $stmt = db()->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return ['ok' => false, 'message' => 'Invalid credentials.'];
    }

    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'is_admin' => $user['role'] === 'admin',
    ];

    return ['ok' => true, 'message' => 'Welcome back!'];
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
