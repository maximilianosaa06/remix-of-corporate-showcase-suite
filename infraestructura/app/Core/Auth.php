<?php

declare(strict_types=1);

namespace App\Core;

class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(int $userId, string $username, int $roleId, string $roleName, bool $mustChangePassword = false): void
    {
        self::start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['role_id'] = $roleId;
        $_SESSION['role_name'] = $roleName;
        $_SESSION['must_change_password'] = $mustChangePassword;
    }

    public static function logout(): void
    {
        self::start();
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }

    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        self::start();
        if (!self::check()) {
            return null;
        }
        return [
            'id'                   => $_SESSION['user_id'],
            'username'             => $_SESSION['username'],
            'role_id'              => $_SESSION['role_id'],
            'role_name'            => $_SESSION['role_name'],
            'must_change_password' => $_SESSION['must_change_password'] ?? false,
        ];
    }

    public static function mustChangePassword(): bool
    {
        self::start();
        return !empty($_SESSION['must_change_password']);
    }

    public static function setMustChangePassword(bool $value): void
    {
        self::start();
        $_SESSION['must_change_password'] = $value;
    }

    public static function hasRole(string ...$roles): bool
    {
        $user = self::user();
        return $user !== null && in_array($user['role_name'], $roles, true);
    }

    public static function requireRole(string ...$roles): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
        if (!self::hasRole(...$roles)) {
            http_response_code(403);
            echo 'Acceso denegado.';
            exit;
        }
    }

    public static function generateCsrfToken(): string
    {
        self::start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken(string $token): bool
    {
        self::start();
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}
