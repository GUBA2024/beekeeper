<?php

declare(strict_types=1);

return [
    'app_name' => 'Golden Hive',
    'base_url' => rtrim((string) (getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? 'http://localhost')), '/'),
    'db' => [
        'host' => (string) ($_ENV['DB_HOST'] ?? '127.0.0.1'),
        'port' => (string) ($_ENV['DB_PORT'] ?? '3306'),
        'name' => (string) ($_ENV['DB_NAME'] ?? 'beekeeper'),
        'user' => (string) ($_ENV['DB_USER'] ?? 'root'),
        'pass' => (string) ($_ENV['DB_PASS'] ?? ''),
        'charset' => 'utf8mb4',
    ],
    'session' => [
        'name' => 'golden_hive_session',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ],
];
