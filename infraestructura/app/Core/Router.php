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

    public function put(string $path, callable|array $handler, array $middleware = []): self
    {
        $this->routes['PUT'][$path] = ['handler' => $handler, 'middleware' => $middleware];
        return $this;
    }

    public function patch(string $path, callable|array $handler, array $middleware = []): self
    {
        $this->routes['PATCH'][$path] = ['handler' => $handler, 'middleware' => $middleware];
        return $this;
    }

    public function delete(string $path, callable|array $handler, array $middleware = []): self
    {
        $this->routes['DELETE'][$path] = ['handler' => $handler, 'middleware' => $middleware];
        return $this;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        if ($method === 'OPTIONS') {
            http_response_code(204);
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');
            header('Access-Control-Max-Age: 86400');
            exit;
        }

        if (isset($this->routes[$method][$uri])) {
            $this->executeRoute($this->routes[$method][$uri]);
            return;
        }

        foreach ($this->routes[$method] as $routePath => $route) {
            $params = $this->matchRoute($routePath, $uri);
            if ($params !== null) {
                $route['_params'] = $params;
                $this->executeRoute($route);
                return;
            }
        }

        if (str_starts_with($uri, '/api/')) {
            \App\Support\Response::notFound('Endpoint no encontrado');
        }

        http_response_code(404);
        require dirname(__DIR__, 2) . '/Views/pages/404.php';
    }

    private function matchRoute(string $routePath, string $uri): ?array
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $uriParts = explode('/', trim($uri, '/'));

        if (count($routeParts) !== count($uriParts)) {
            return null;
        }

        $params = [];
        foreach ($routeParts as $i => $part) {
            if (preg_match('/^\{(\w+)\}$/', $part, $matches)) {
                $params[$matches[1]] = $uriParts[$i];
            } elseif ($part !== $uriParts[$i]) {
                return null;
            }
        }

        return $params;
    }

    private function executeRoute(array $route): void
    {
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

            if (isset($route['_params'])) {
                $controller->$action(...$route['_params']);
            } else {
                $controller->$action();
            }
        } elseif (is_callable($handler)) {
            if (isset($route['_params'])) {
                $handler(...$route['_params']);
            } else {
                $handler();
            }
        }
    }
}
