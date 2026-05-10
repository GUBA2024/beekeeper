<?php

declare(strict_types=1);

$config = require __DIR__ . '/config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name($config['session']['name']);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $config['session']['secure'],
        'httponly' => $config['session']['httponly'],
        'samesite' => $config['session']['samesite'],
    ]);
    session_start();
}
