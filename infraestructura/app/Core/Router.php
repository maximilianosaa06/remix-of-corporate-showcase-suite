<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler, array $middleware = []): self
    {
        $this->routes['GET'][$path] = ['handler' => $handler, 'middleware' => $middleware];
        return $this;
    }

    public function post(string $path, callable|array $handler, array $middleware = []): self
    {
        $this->routes['POST'][$path] = ['handler' => $handler, 'middleware' => $middleware];
        return $this;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            require dirname(__DIR__, 2) . '/Views/pages/404.php';
            return;
        }

        $route = $this->routes[$method][$uri];

        foreach ($route['middleware'] as $mwName) {
            match ($mwName) {
                'csrf'  => Middleware::csrf(),
                'auth'  => Middleware::auth(),
                'guest' => Middleware::guest(),
                'force_password_change' => Middleware::forcePasswordChange(),
                default => null,
            };
        }

        $handler = $route['handler'];

        if (is_array($handler)) {
            [$class, $action] = $handler;
            $controller = new $class();
            $controller->$action();
        } elseif (is_callable($handler)) {
            $handler();
        }
    }
}
