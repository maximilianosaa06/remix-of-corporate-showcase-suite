<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Services\NewsService;
use App\Core\Auth;
use App\Support\Response;

class NewsController
{
    private NewsService $service;

    public function __construct()
    {
        $this->service = new NewsService();
    }

    public function index(): void
    {
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, min(100, (int) ($_GET['per_page'] ?? 20)));
        $isAdmin = Auth::check() && in_array(Auth::user()['role_name'] ?? '', ['superadmin', 'admin', 'editor'], true);

        $data = $isAdmin
            ? $this->service->getAll($page, $perPage)
            : $this->service->getPublished($page, $perPage);

        Response::success($data);
    }

    public function show(int $id): void
    {
        $isAdmin = Auth::check() && in_array(Auth::user()['role_name'] ?? '', ['superadmin', 'admin', 'editor'], true);

        $item = $isAdmin
            ? $this->service->getById($id)
            : $this->service->getPublishedById($id);

        if (!$item) {
            Response::notFound();
            return;
        }

        Response::success($item);
    }

    public function store(): void
    {
        if (!Auth::check()) {
            Response::unauthorized();
            return;
        }

        $data = $this->getJsonInput();

        try {
            $item = $this->service->create($data, (int) Auth::user()['id']);
            Response::created($item);
        } catch (\InvalidArgumentException $e) {
            Response::unprocessable(json_decode($e->getMessage(), true));
        } catch (\Exception $e) {
            Response::serverError();
        }
    }

    public function update(int $id): void
    {
        if (!Auth::check()) {
            Response::unauthorized();
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
        if (!Auth::check()) {
            Response::unauthorized();
            return;
        }

        $data = $this->getJsonInput();
        $status = $data['status'] ?? '';

        if ($status === '') {
            Response::badRequest(['status' => 'El campo status es obligatorio.']);
            return;
        }

        try {
            $item = $this->service->updateStatus($id, $status);
            Response::success($item);
        } catch (\InvalidArgumentException $e) {
            Response::unprocessable(json_decode($e->getMessage(), true));
        } catch (\RuntimeException $e) {
            Response::notFound();
        } catch (\Exception $e) {
            Response::serverError();
        }
    }

    private function getJsonInput(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
