<?php
/**
 * Golden Hive – One-click installer
 *
 * Open this file in the browser ONCE after placing the project under htdocs:
 *   http://localhost/beekeeper/setup.php
 *
 * It will create the database, tables, and seed the sample data.
 * Delete or rename this file after a successful setup.
 */

declare(strict_types=1);

// ── Configuration ──────────────────────────────────────────────────────────
$dbHost = 'localhost';
$dbPort = '3306';
$dbName = 'beekeeper';
$dbUser = 'root';
$dbPass = '';          // XAMPP default: empty password

// Allow overrides via the same env vars the app uses
if (($v = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '')) !== '') $dbHost = $v;
if (($v = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '')) !== '') $dbPort = $v;
if (($v = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? '')) !== '') $dbName = $v;
if (($v = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? '')) !== '') $dbUser = $v;
if (($v = getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? '')) !== '') $dbPass = $v;
// ───────────────────────────────────────────────────────────────────────────

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Override from form if submitted
    $dbHost = trim((string) ($_POST['db_host'] ?? $dbHost));
    $dbPort = trim((string) ($_POST['db_port'] ?? $dbPort));
    $dbName = trim((string) ($_POST['db_name'] ?? $dbName));
    $dbUser = trim((string) ($_POST['db_user'] ?? $dbUser));
    $dbPass = (string) ($_POST['db_pass'] ?? $dbPass);

    try {
        // Connect without selecting a database first so we can CREATE it
        $dsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $sql = file_get_contents(__DIR__ . '/db/schema.sql');
        if ($sql === false) {
            throw new RuntimeException('Cannot read db/schema.sql');
        }

        // Split on semicolons and execute each statement
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
            if ($stmt !== '') {
                $pdo->exec($stmt);
            }
        }

        $success = true;
    } catch (Throwable $e) {
        $errors[] = $e->getMessage();
    }
}

$e = fn(string $v): string => htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Golden Hive – Setup</title>
<style>
  body { font-family: 'Segoe UI', sans-serif; background: #fff8e1; color: #3e2723; margin: 0; display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; padding: 2rem 1rem; box-sizing: border-box; }
  .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.10); padding: 2.5rem; width: 100%; max-width: 480px; }
  h1 { margin-top: 0; font-size: 1.6rem; color: #f4b400; }
  label { display: block; margin-bottom: 1rem; font-weight: 600; font-size: .9rem; }
  input { display: block; width: 100%; padding: .55rem .75rem; border: 1.5px solid #ddd; border-radius: 8px; font-size: 1rem; box-sizing: border-box; margin-top: .3rem; }
  button { display: block; width: 100%; padding: .75rem; background: #f4b400; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; color: #3e2723; cursor: pointer; margin-top: 1.5rem; }
  button:hover { background: #e5a800; }
  .alert { padding: .9rem 1rem; border-radius: 10px; margin-bottom: 1.4rem; font-size: .92rem; }
  .error { background: #fdecea; color: #c62828; }
  .success { background: #e8f5e9; color: #2e7d32; }
  .note { font-size: .82rem; color: #888; margin-top: 1.4rem; }
  a { color: #f4b400; }
</style>
</head>
<body>
<div class="card">
  <h1>🍯 Golden Hive – Setup</h1>

  <?php if ($success): ?>
  <div class="alert success">
    ✅ <strong>Database created and seeded successfully!</strong><br>
    You can now <a href="index.php">open the site</a>.<br><br>
    <strong>⚠️ Delete or rename <code>setup.php</code></strong> once you're done.
  </div>
  <p><strong>Sample login credentials:</strong></p>
  <ul>
    <li>Admin: <code>admin@goldenhive.local</code></li>
    <li>Customer: <code>sara@example.com</code></li>
    <li>Password (both): <code>password123</code></li>
  </ul>
  <?php else: ?>

  <?php if ($errors): ?>
  <div class="alert error">
    <strong>Error:</strong><br>
    <?php foreach ($errors as $err): ?>
      <?= $e($err) ?><br>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <p>Fill in your MySQL credentials and click <strong>Run Setup</strong>. On XAMPP the defaults below are usually correct (no password).</p>

  <form method="post">
    <label>Host
      <input name="db_host" value="<?= $e($dbHost) ?>" required>
    </label>
    <label>Port
      <input name="db_port" value="<?= $e($dbPort) ?>" required>
    </label>
    <label>Database name
      <input name="db_name" value="<?= $e($dbName) ?>" required>
    </label>
    <label>Username
      <input name="db_user" value="<?= $e($dbUser) ?>" required>
    </label>
    <label>Password
      <input name="db_pass" type="password" value="" placeholder="(leave blank for XAMPP default)">
    </label>
    <button type="submit">▶ Run Setup</button>
  </form>
  <?php endif; ?>

  <p class="note">This script is only for local/development setup. Remove it before going live.</p>
</div>
</body>
</html>
