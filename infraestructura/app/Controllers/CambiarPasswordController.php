<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Services\AuditService;

class CambiarPasswordController
{
    private AuditService $audit;

    public function __construct()
    {
        $this->audit = new AuditService();
    }

    public function showForm(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $user = Auth::user();
        $db = Database::getInstance();
        $userData = $db->fetchOne(
            "SELECT must_change_password FROM app_user WHERE id = :id",
            ['id' => $user['id']]
        );

        if (!$userData || !$userData['must_change_password']) {
            header('Location: /admin');
            exit;
        }

        $error = '';
        $success = '';
        $pageTitle = 'Cambiar contraseña — TECH HUB ULS';

        require dirname(__DIR__, 2) . '/Views/layouts/header.php';
        require dirname(__DIR__, 2) . '/Views/pages/cambiar_password.php';
        require dirname(__DIR__, 2) . '/Views/layouts/footer.php';
    }

    public function changePassword(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $user = Auth::user();
        $db = Database::getInstance();
        $userData = $db->fetchOne(
            "SELECT must_change_password, password FROM app_user WHERE id = :id",
            ['id' => $user['id']]
        );

        if (!$userData || !$userData['must_change_password']) {
            header('Location: /admin');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $error = '';

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $error = 'Todos los campos son obligatorios.';
        } elseif (!password_verify($currentPassword, $userData['password'])) {
            $error = 'La contraseña actual es incorrecta.';
        } elseif (strlen($newPassword) < 8) {
            $error = 'La nueva contraseña debe tener al menos 8 caracteres.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Las contraseñas nuevas no coinciden.';
        } elseif ($currentPassword === $newPassword) {
            $error = 'La nueva contraseña debe ser diferente a la actual.';
        }

        if ($error) {
            $success = '';
            $pageTitle = 'Cambiar contraseña — TECH HUB ULS';
            require dirname(__DIR__, 2) . '/Views/layouts/header.php';
            require dirname(__DIR__, 2) . '/Views/pages/cambiar_password.php';
            require dirname(__DIR__, 2) . '/Views/layouts/footer.php';
            return;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $db->update('app_user', [
            'password'              => $hashedPassword,
            'must_change_password'  => false,
        ], 'id = :id', ['id' => $user['id']]);

        $this->audit->log('password_change', 'app_user', (int) $user['id'], $user['username']);

        $success = 'Contraseña cambiada exitosamente.';
        $error = '';
        $pageTitle = 'Cambiar contraseña — TECH HUB ULS';
        require dirname(__DIR__, 2) . '/Views/layouts/header.php';
        require dirname(__DIR__, 2) . '/Views/pages/cambiar_password.php';
        require dirname(__DIR__, 2) . '/Views/layouts/footer.php';
    }
}
