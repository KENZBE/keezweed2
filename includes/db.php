<?php
declare(strict_types=1);
// PDO bootstrap. Exposes $pdo variable for includes that require DB access.
// Requires: includes/db-settings.php returning array with dsn,user,pass

$cfgPath = __DIR__ . '/db-settings.php';
// Ensure .env is loaded for environments that don't support SetEnv (admin pages
// may include db.php directly before includes/shell.php is loaded).
@require_once __DIR__ . '/load_env.php';
if (!is_file($cfgPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'DB settings not found: includes/db-settings.php']);
    exit;
}

$dbCfg = require $cfgPath;
if (($dbCfg['dsn'] ?? '') === '' || ($dbCfg['user'] ?? '') === '') {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Database configuration missing',
        'message' => 'Set DATABASE_URL or SKUSHDROP_DB_DSN, SKUSHDROP_DB_USER and SKUSHDROP_DB_PASS in the hosting environment.',
    ]);
    exit;
}

try {
    $pdo = new PDO($dbCfg['dsn'], $dbCfg['user'], $dbCfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Database connection failed. Check the hosting logs and database environment variables.']);
    exit;
}
