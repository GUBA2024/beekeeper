<?php

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = require __DIR__ . '/config.php';
    $db = $config['db'];
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $db['host'], $db['port'], $db['name'], $db['charset']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        // Show a clear setup instruction instead of a blank/cryptic error page.
        $base = defined('APP_BASE') ? APP_BASE : '';
        $setupUrl = $base . '/setup.php';
        http_response_code(503);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width,initial-scale=1">'
            . '<title>Golden Hive – Database Error</title>'
            . '<style>body{font-family:sans-serif;background:#fff8e1;color:#3e2723;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0}'
            . '.box{background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.1);padding:2.5rem;max-width:480px;width:100%;margin:1rem}'
            . 'h1{color:#c62828;margin-top:0}a{color:#f4b400}'
            . '</style></head><body><div class="box">'
            . '<h1>⚠️ Database not connected</h1>'
            . '<p>Could not connect to MySQL. Make sure:</p>'
            . '<ol>'
            . '<li><strong>XAMPP → MySQL is started</strong> (green status in XAMPP Control Panel)</li>'
            . '<li>The database <code>beekeeper</code> has been created. '
            . '<a href="' . htmlspecialchars($setupUrl, ENT_QUOTES, 'UTF-8') . '">Run the setup wizard ▶</a></li>'
            . '</ol>'
            . '<p style="font-size:.85rem;color:#888">Technical detail: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>'
            . '</div></body></html>';
        exit;
    }

    return $pdo;
}
