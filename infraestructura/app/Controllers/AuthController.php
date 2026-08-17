<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;

class AuthController
{
    public function loginForm(): void
    {
        $pageTitle       = 'Iniciar sesión — TECH HUB ULS';
        $pageDescription = 'Acceso al panel de administración.';
        $error = '';

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require dirname(__DIR__) . '/Views/pages/login.php';
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }

    public function login(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $pageTitle       = 'Iniciar sesión — TECH HUB ULS';
            $pageDescription = 'Acceso al panel de administración.';
            $error = 'Ingrese correo y contraseña.';
            require dirname(__DIR__) . '/Views/layouts/header.php';
            require dirname(__DIR__) . '/Views/pages/login.php';
            require dirname(__DIR__) . '/Views/layouts/footer.php';
            return;
        }

        $db = Database::getInstance();
        $user = $db->fetchOne(
            "SELECT u.id, u.username, u.email, u.password, u.active, u.must_change_password,
                    r.id AS role_id, r.name AS role_name
             FROM app_user u
             JOIN role r ON u.role_id = r.id
             WHERE u.email = :email AND u.active = true",
            ['email' => $email]
        );

        if (!$user || !password_verify($password, $user['password'])) {
            $pageTitle       = 'Iniciar sesión — TECH HUB ULS';
            $pageDescription = 'Acceso al panel de administración.';
            $error = 'Credenciales inválidas.';
            require dirname(__DIR__) . '/Views/layouts/header.php';
            require dirname(__DIR__) . '/Views/pages/login.php';
            require dirname(__DIR__) . '/Views/layouts/footer.php';
            return;
        }

        Auth::login(
            (int) $user['id'],
            $user['username'],
            (int) $user['role_id'],
            $user['role_name'],
            (bool) $user['must_change_password']
        );

        if ($user['must_change_password']) {
            header('Location: /cambiar-password');
        } else {
            header('Location: /admin');
        }
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: /');
        exit;
    }
}
