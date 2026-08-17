<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Services\UserService;
use App\Core\Auth;
use App\Support\Response;

class AuthController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function login(): void
    {
        $data = $this->getJsonInput();

        if (empty($data['email']) || empty($data['password'])) {
            Response::badRequest(['email' => 'Email y contraseña son obligatorios.']);
            return;
        }

        $user = $this->userService->getByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::unauthorized(['general' => 'Credenciales inválidas.']);
            return;
        }

        if (!$user['active']) {
            Response::forbidden(['general' => 'La cuenta está desactivada.']);
            return;
        }

        Auth::login(
            (int) $user['id'],
            $user['username'],
            (int) $user['role_id'],
            $user['role_name'],
            (bool) $user['must_change_password']
        );

        Response::success([
            'user' => [
                'id'                   => $user['id'],
                'username'             => $user['username'],
                'email'                => $user['email'],
                'must_change_password' => (bool) $user['must_change_password'],
                'role'                 => ['id' => $user['role_id'], 'name' => $user['role_name']],
            ],
        ]);
    }

    public function logout(): void
    {
        Auth::logout();
        Response::noContent();
    }

    public function me(): void
    {
        if (!Auth::check()) {
            Response::unauthorized();
            return;
        }

        $user = Auth::user();
        $repo = new \App\Api\Repositories\UserRepository();
        $full = $repo->findById((int) $user['id']);

        if (!$full) {
            Response::notFound();
            return;
        }

        Response::success(['user' => $full]);
    }

    private function getJsonInput(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
