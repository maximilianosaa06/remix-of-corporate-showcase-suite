<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Services\UserService;
use App\Core\Auth;
use App\Support\Response;

class UserController
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function index(): void
    {
        if (!$this->isSuperAdmin()) {
            Response::forbidden();
            return;
        }

        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, min(100, (int) ($_GET['per_page'] ?? 20)));

        $data = $this->service->getAll($page, $perPage);
        Response::success($data);
    }

    public function show(int $id): void
    {
        if (!$this->isSuperAdmin()) {
            Response::forbidden();
            return;
        }

        $item = $this->service->getById($id);

        if (!$item) {
            Response::notFound();
            return;
        }

        Response::success($item);
    }

    public function store(): void
    {
        if (!$this->isSuperAdmin()) {
            Response::forbidden();
            return;
        }

        $data = $this->getJsonInput();

        try {
            $item = $this->service->create($data);
            Response::created($item);
        } catch (\InvalidArgumentException $e) {
            Response::unprocessable(json_decode($e->getMessage(), true));
        } catch (\Exception $e) {
            Response::serverError();
        }
    }

    public function update(int $id): void
    {
        if (!$this->isSuperAdmin()) {
            Response::forbidden();
            return;
        }

        $data = $this->getJsonInput();

        try {
            $item = $this->service->update($id, $data);
            Response::success($item);
        } catch (\RuntimeException $e) {
            Response::notFound();
        } catch (\InvalidArgumentException $e) {
            Response::unprocessable(json_decode($e->getMessage(), true));
        } catch (\Exception $e) {
            Response::serverError();
        }
    }

    public function destroy(int $id): void
    {
        if (!$this->isSuperAdmin()) {
            Response::forbidden();
            return;
        }

        try {
            $this->service->delete($id);
            Response::noContent();
        } catch (\RuntimeException $e) {
            Response::notFound();
        } catch (\Exception $e) {
            Response::serverError();
        }
    }

    public function roles(): void
    {
        if (!$this->isSuperAdmin()) {
            Response::forbidden();
            return;
        }

        $roles = $this->service->getRoles();
        Response::success($roles);
    }

    private function isSuperAdmin(): bool
    {
        if (!Auth::check()) return false;
        return (Auth::user()['role_name'] ?? '') === 'superadmin';
    }

    private function getJsonInput(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
