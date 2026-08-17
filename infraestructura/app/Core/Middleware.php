<?php

declare(strict_types=1);

namespace App\Core;

use App\Support\Response;

class Middleware
{
    public static function csrf(): void
    {
        Auth::start();

        if (self::isApiRequest()) {
            return;
        }

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
            if (self::isApiRequest()) {
                Response::unauthorized();
            }
            header('Location: /login');
            exit;
        }
    }

    public static function guest(): void
    {
        if (Auth::check()) {
            if (self::isApiRequest()) {
                Response::error('ALREADY_AUTHENTICATED', 'Ya está autenticado.', 409);
            }
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
                if (self::isApiRequest()) {
                    Response::error('PASSWORD_CHANGE_REQUIRED', 'Debe cambiar su contraseña.', 403);
                }
                header('Location: /cambiar-password');
                exit;
            }
        }
    }

    private static function isApiRequest(): bool
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return str_starts_with($uri, '/api/');
    }
}
