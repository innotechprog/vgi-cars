<?php

class Auth
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(array $user): void
    {
        $_SESSION['user_id'] = (int) $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'] ?? 'admin';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
    }

    public function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function role(): string
    {
        return (string) ($_SESSION['role'] ?? '');
    }

    public function hasRole(string $role): bool
    {
        return $this->role() === $role;
    }

    public function requireLogin(string $redirect = 'login.php'): void
    {
        if (!$this->check()) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    public function requireRole(string $role, string $redirect = 'login.php'): void
    {
        $this->requireLogin($redirect);
        if (!$this->hasRole($role)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }

    public function id(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }
}
