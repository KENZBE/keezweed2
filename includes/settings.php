<?php
declare(strict_types=1);

function site_settings_file(): string
{
    return __DIR__ . '/site-settings.json';
}

function default_site_settings(): array
{
    return [
        'telegram_url' => 'https://t.me/Paulduran13',
        'whatsapp_url' => '',
        'updated_at' => null,
    ];
}

function load_site_settings(): array
{
    $defaults = default_site_settings();

    if (site_settings_database_configured()) {
        global $pdo;
        require_once __DIR__ . '/db.php';
        ensure_site_settings_schema($pdo);
        $statement = $pdo->query('SELECT setting_key, setting_value FROM site_settings');
        foreach ($statement->fetchAll() as $row) {
            if (array_key_exists($row['setting_key'], $defaults)) {
                $defaults[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $defaults;
    }

    $path = site_settings_file();
    if (is_file($path)) {
        $decoded = json_decode((string) file_get_contents($path), true);
        if (is_array($decoded)) {
            $defaults = array_merge($defaults, array_intersect_key($decoded, $defaults));
        }
    }

    return $defaults;
}

function site_settings_database_configured(): bool
{
    $config = require __DIR__ . '/db-settings.php';
    return ($config['dsn'] ?? '') !== '' && ($config['user'] ?? '') !== '';
}

function ensure_site_settings_schema(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
        setting_key VARCHAR(80) PRIMARY KEY,
        setting_value TEXT NOT NULL
    )");
}

function normalize_contact_url(string $service, string $value): ?string
{
    $value = trim($value);
    if ($value === '') {
        return $service === 'whatsapp' ? '' : null;
    }

    if ($service === 'telegram' && preg_match('/^@?([A-Za-z0-9_]{5,32})$/', $value, $match)) {
        return 'https://t.me/' . $match[1];
    }

    if ($service === 'whatsapp' && preg_match('/^\+?[0-9 ()-]{8,24}$/', $value)) {
        $number = preg_replace('/\D+/', '', $value);
        return $number !== '' ? 'https://wa.me/' . $number : null;
    }

    $parts = parse_url($value);
    if (!is_array($parts) || !in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true)) {
        return null;
    }

    $host = strtolower((string) ($parts['host'] ?? ''));
    $allowedHosts = $service === 'telegram'
        ? ['t.me', 'www.t.me', 'telegram.me', 'www.telegram.me']
        : ['wa.me', 'www.wa.me', 'api.whatsapp.com', 'www.api.whatsapp.com'];

    return in_array($host, $allowedHosts, true) ? $value : null;
}

function save_site_settings(array $settings): bool
{
    if (site_settings_database_configured()) {
        global $pdo;
        require_once __DIR__ . '/db.php';
        ensure_site_settings_schema($pdo);

        $pdo->beginTransaction();
        try {
            $delete = $pdo->prepare('DELETE FROM site_settings WHERE setting_key = :key');
            $insert = $pdo->prepare('INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :value)');
            foreach (['telegram_url', 'whatsapp_url'] as $key) {
                $delete->execute([':key' => $key]);
                $insert->execute([':key' => $key, ':value' => (string) ($settings[$key] ?? '')]);
            }
            $pdo->commit();
            return true;
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Saving site contact settings failed: ' . $error->getMessage());
            return false;
        }
    }

    $payload = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return is_string($payload)
        && file_put_contents(site_settings_file(), $payload . PHP_EOL, LOCK_EX) !== false;
}
