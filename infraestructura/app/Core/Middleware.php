<?php

declare(strict_types=1);

namespace App\Core;

class Middleware
{
    public static function csrf(): void
    {
        Auth::start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if (!Auth::verifyCsrfToken($token)) {
                http_response_code(403);
                echo 'Token CSRF inválido.';
                exit;
            }
        }
    }

    public static function auth(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function guest(): void
    {
        if (Auth::check()) {
            header('Location: /admin');
            exit;
        }
    }

    public static function role(string ...$roles): void
    {
        Auth::requireRole(...$roles);
    }

    public static function forcePasswordChange(): void
    {
        if (Auth::check() && Auth::mustChangePassword()) {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri = rtrim($uri, '/') ?: '/';
            if ($uri !== '/cambiar-password' && $uri !== '/logout') {
                header('Location: /cambiar-password');
                exit;
            }
        }
    }
}
