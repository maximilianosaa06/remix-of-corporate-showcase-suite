<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Services\ServiceService;
use App\Core\Auth;
use App\Support\Response;

class ServiceController
{
    private ServiceService $service;

    public function __construct()
    {
        $this->service = new ServiceService();
    }

    public function index(): void
    {
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, min(100, (int) ($_GET['per_page'] ?? 20)));
        $isAdmin = $this->isAdminOrEditor();

        $data = $this->service->getAll($page, $perPage, $isAdmin);
        Response::success($data);
    }

    public function show(int $id): void
    {
        $item = $this->service->getById($id);

        if (!$item) {
            Response::notFound();
            return;
        }

        if (!$item['active'] && !$this->isAdminOrEditor()) {
            Response::notFound();
            return;
        }

        Response::success($item);
    }

    public function store(): void
    {
        if (!$this->isAdminOrEditor()) {
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
        if (!$this->isAdminOrEditor()) {
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

    public function updateStatus(int $id): void
    {
        if (!$this->isAdminOrEditor()) {
            Response::forbidden();
            return;
        }

        $data = $this->getJsonInput();
        $active = $data['active'] ?? null;

        if ($active === null) {
            Response::badRequest(['active' => 'El campo active es obligatorio.']);
            return;
        }

        try {
            $item = $this->service->setStatus($id, (bool) $active);
            Response::success($item);
        } catch (\RuntimeException $e) {
            Response::notFound();
        } catch (\Exception $e) {
            Response::serverError();
        }
    }

    public function destroy(int $id): void
    {
        if (!$this->isAdminOrEditor()) {
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

    private function isAdminOrEditor(): bool
    {
        if (!Auth::check()) return false;
        return in_array(Auth::user()['role_name'] ?? '', ['superadmin', 'admin', 'editor'], true);
    }

    private function getJsonInput(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
