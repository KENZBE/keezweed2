<?php
declare(strict_types=1);

$getenv = static function (string $key): string {
    $v = getenv($key);
    if ($v === false || $v === null || trim((string) $v) === '') {
        $v = $_ENV[$key] ?? $_SERVER[$key] ?? '';
    }
    return trim((string) $v);
};

$dsn = $getenv('SKUSHDROP_DB_DSN');
$user = $getenv('SKUSHDROP_DB_USER');
$pass = $getenv('SKUSHDROP_DB_PASS');
$databaseUrl = $getenv('DATABASE_URL');

if ($dsn === '' && $databaseUrl !== '') {
    $parts = parse_url($databaseUrl);
    if (is_array($parts)
        && in_array(strtolower((string) ($parts['scheme'] ?? '')), ['postgres', 'postgresql'], true)
        && isset($parts['host'], $parts['path'])) {
        parse_str((string) ($parts['query'] ?? ''), $query);
        $sslMode = (string) ($query['sslmode'] ?? 'require');
        if (!in_array($sslMode, ['require', 'verify-ca', 'verify-full'], true)) {
            $sslMode = 'require';
        }
        $dsn = 'pgsql:host=' . $parts['host']
            . ';port=' . (int) ($parts['port'] ?? 5432)
            . ';dbname=' . rawurldecode(ltrim((string) $parts['path'], '/'))
            . ';sslmode=' . $sslMode;
        $user = rawurldecode((string) ($parts['user'] ?? ''));
        $pass = rawurldecode((string) ($parts['pass'] ?? ''));
    }
}

return [
    'dsn' => $dsn,
    'user' => $user,
    'pass' => $pass,
];
