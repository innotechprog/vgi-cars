<?php

class Database
{
    private $host = '127.0.0.1';
    private $db_name = 'vgi';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $conn;

    public static function resolveConfig(): array
    {
        $config = [
            'host' => self::env('DB_HOST', '127.0.0.1'),
            'name' => self::env('DB_NAME', 'vgi'),
            'user' => self::env('DB_USER', 'root'),
            'pass' => self::env('DB_PASS', ''),
            'charset' => self::env('DB_CHARSET', 'utf8mb4'),
        ];

        $hasExplicitEnv = (
            self::env('DB_HOST') !== null ||
            self::env('DB_NAME') !== null ||
            self::env('DB_USER') !== null ||
            self::env('DB_PASS') !== null
        );

        $isWebRequest = PHP_SAPI !== 'cli';
        $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
        $isLocalHost = self::isLocalHost($host);

        if (!$hasExplicitEnv && $isWebRequest && !$isLocalHost) {
            $legacy = self::parseLegacyOnlineSetup();
            if (!empty($legacy)) {
                $config = array_replace($config, $legacy);
            }
        }

        return $config;
    }

    public function __construct(array $config = [])
    {
        if (empty($config)) {
            $config = self::resolveConfig();
        }

        $this->host = (string) ($config['host'] ?? $this->host);
        $this->db_name = (string) ($config['name'] ?? $this->db_name);
        $this->username = (string) ($config['user'] ?? $this->username);
        $this->password = (string) ($config['pass'] ?? $this->password);
        $this->charset = (string) ($config['charset'] ?? $this->charset);
    }

    public function connect(): PDO
    {
        if ($this->conn instanceof PDO) {
            return $this->conn;
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $this->host,
            $this->db_name,
            $this->charset
        );

        $this->conn = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $this->conn;
    }

    public function pdo(): PDO
    {
        return $this->connect();
    }

    private static function env(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        if ($value === false || $value === null || $value === '') {
            $value = $_ENV[$key] ?? ($_SERVER[$key] ?? null);
        }

        if ($value === null || $value === '') {
            return $default;
        }

        return (string) $value;
    }

    private static function isLocalHost(string $host): bool
    {
        $host = strtolower(trim($host));
        if ($host === '') {
            return false;
        }

        $host = preg_replace('/:\\d+$/', '', $host);
        return in_array($host, ['localhost', '127.0.0.1', '::1'], true)
            || str_ends_with($host, '.local');
    }

    private static function parseLegacyOnlineSetup(): array
    {
        $filePath = dirname(__DIR__, 3) . '/includes/online setup.php';
        if (!is_file($filePath)) {
            return [];
        }

        $content = (string) @file_get_contents($filePath);
        if ($content === '') {
            return [];
        }

        $map = [
            'host' => 'host',
            'db_name' => 'name',
            'username' => 'user',
            'password' => 'pass',
        ];

        $db = [];
        foreach ($map as $legacyKey => $configKey) {
            $pattern = '/\\$' . preg_quote($legacyKey, '/') . '\\s*=\\s*[\"\']([^\"\']+)[\"\']\\s*;/';
            if (preg_match($pattern, $content, $match) === 1) {
                $db[$configKey] = $match[1];
            }
        }

        return $db;
    }
}