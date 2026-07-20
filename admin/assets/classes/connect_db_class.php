<?php

class Database
{
    private $host = '127.0.0.1';
    private $db_name = 'vgi';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $conn;

    public function __construct(array $config = [])
    {
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
}