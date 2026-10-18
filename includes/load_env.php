<?php
declare(strict_types=1);

// Simple .env loader for environments that don't support SetEnv.
// Reads lines KEY="value" or KEY=value and exports them to getenv()/\$_ENV/\$_SERVER.
$candidates = [
    // Preferred: local development overrides
    __DIR__ . '/../.env.local',
    // Production file
    __DIR__ . '/../.env.production',
    // Fallback for compatibility
    __DIR__ . '/../.env',
];
$envFile = null;
foreach ($candidates as $candidate) {
    if (is_readable($candidate)) {
        $envFile = $candidate;
        break;
    }
}
if ($envFile !== null) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        $v = trim($v, "\"'");
        if ($k === '') continue;
        $processValue = getenv($k);
        if ($processValue !== false && trim($processValue) !== '') {
            $_ENV[$k] = $processValue;
            $_SERVER[$k] = $processValue;
            continue;
        }
        putenv("$k=$v");
        $_ENV[$k] = $v;
        $_SERVER[$k] = $v;
    }
}
