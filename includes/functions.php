<?php

declare(strict_types=1);

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

// Compute APP_BASE: the URL subdirectory path the app is served from.
// Auto-detects from DOCUMENT_ROOT so the app works both at domain root
// (http://localhost/) and in a subdirectory (http://localhost/beekeeper/).
// Override by setting the APP_URL environment variable, e.g. APP_URL=http://localhost/beekeeper
if (!defined('APP_BASE')) {
    $appUrlEnv = (string) (getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? ($_SERVER['APP_URL'] ?? '')));
    if ($appUrlEnv !== '') {
        $parsed = parse_url(rtrim($appUrlEnv, '/'));
        define('APP_BASE', rtrim((string) ($parsed['path'] ?? ''), '/'));
    } elseif (!empty($_SERVER['DOCUMENT_ROOT'])) {
        // Strip the document root from the project directory to get the subpath.
        // E.g. docRoot=/var/www/html, projectDir=/var/www/html/beekeeper → APP_BASE='/beekeeper'
        // At domain root they are equal → APP_BASE=''
        $docRoot = rtrim(str_replace('\\', '/', (string) realpath($_SERVER['DOCUMENT_ROOT'])), '/');
        $projectDir = rtrim(str_replace('\\', '/', (string) realpath(__DIR__ . '/..')), '/');
        define('APP_BASE', ($docRoot !== '' && str_starts_with($projectDir, $docRoot))
            ? rtrim(substr($projectDir, strlen($docRoot)), '/')
            : '');
    } else {
        define('APP_BASE', '');
    }
}

/**
 * Generate a URL for an internal page/endpoint, respecting the base path.
 */
function url(string $path): string
{
    return APP_BASE . '/' . ltrim($path, '/');
}

/**
 * Generate a URL for a static asset, respecting the base path.
 */
function asset(string $path): string
{
    return url($path);
}

/**
 * Return a full URL for a stored image path.
 * Full URLs (http/https) are returned unchanged; relative paths get the base prefix.
 */
function asset_url(string $path): string
{
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return url($path);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_validate(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_authenticated(): bool
{
    return current_user() !== null;
}

function is_admin(): bool
{
    return !empty($_SESSION['user']['is_admin']);
}

function require_auth(): void
{
    if (!is_authenticated()) {
        header('Location: ' . url('auth.php?mode=login'));
        exit;
    }
}

function require_admin(): void
{
    if (!is_admin()) {
        http_response_code(403);
        exit('Forbidden');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $messages;
}

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function request_json(): array
{
    $raw = file_get_contents('php://input') ?: '{}';
    $decoded = json_decode($raw, true);

    return is_array($decoded) ? $decoded : [];
}
