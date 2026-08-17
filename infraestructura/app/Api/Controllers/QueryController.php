<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Services\QueryService;
use App\Core\Auth;
use App\Support\Response;

class QueryController
{
    private QueryService $service;

    public function __construct()
    {
        $this->service = new QueryService();
    }

    public function store(): void
    {
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

    public function index(): void
    {
        if (!$this->isAdminOrEditor()) {
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
        if (!$this->isAdminOrEditor()) {
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

    public function updateStatus(int $id): void
    {
        if (!$this->isAdminOrEditor()) {
            Response::forbidden();
            return;
        }

        $data = $this->getJsonInput();
        $status = $data['status'] ?? '';

        if ($status === '') {
            Response::badRequest(['status' => 'El campo status es obligatorio.']);
            return;
        }

        try {
            $item = $this->service->setStatus($id, $status);
            Response::success($item);
        } catch (\InvalidArgumentException $e) {
            Response::unprocessable(json_decode($e->getMessage(), true));
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
