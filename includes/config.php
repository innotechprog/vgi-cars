<?php

/**
 * Read environment values from getenv/$_ENV/$_SERVER with fallback.
 */
function cfg_env(string $key, $default = null)
{
    $value = getenv($key);
    if ($value === false || $value === null || $value === '') {
        $value = $_ENV[$key] ?? ($_SERVER[$key] ?? null);
    }

    if ($value === null || $value === '') {
        return $default;
    }

    return $value;
}

$config = [
    'db' => [
        'host' => (string) cfg_env('DB_HOST', '127.0.0.1'),
        'name' => (string) cfg_env('DB_NAME', 'vgi'),
        'user' => (string) cfg_env('DB_USER', 'root'),
        'pass' => (string) cfg_env('DB_PASS', ''),
        'charset' => (string) cfg_env('DB_CHARSET', 'utf8mb4'),
    ],
    'app' => [
        'base_url' => (string) cfg_env('APP_BASE_URL', '/don joachim/vgi'),
        'uploads_dir' => __DIR__ . '/../uploads/cars',
        'uploads_web' => (string) cfg_env('UPLOADS_WEB', 'uploads/cars'),
    ],
    'mail' => [
        'enabled' => in_array(strtolower((string) cfg_env('MAIL_ENABLED', '0')), ['1', 'true', 'yes'], true),
        'host' => (string) cfg_env('MAIL_HOST', ''),
        'port' => (int) cfg_env('MAIL_PORT', 587),
        'username' => (string) cfg_env('MAIL_USERNAME', ''),
        'password' => (string) cfg_env('MAIL_PASSWORD', ''),
        'encryption' => (string) cfg_env('MAIL_ENCRYPTION', 'tls'),
        'from_email' => (string) cfg_env('MAIL_FROM_EMAIL', ''),
        'from_name' => (string) cfg_env('MAIL_FROM_NAME', 'VGI Cars'),
        'to_email' => (string) cfg_env('MAIL_TO_EMAIL', ''),
    ],
];

$localConfig = __DIR__ . '/config.local.php';
if (is_file($localConfig)) {
    $overrides = require $localConfig;
    if (is_array($overrides)) {
        $config = array_replace_recursive($config, $overrides);
    }
}

return $config;
