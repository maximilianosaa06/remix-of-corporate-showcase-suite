<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Services\TagService;
use App\Core\Auth;
use App\Support\Response;

class TagController
{
    private TagService $service;

    public function __construct()
    {
        $this->service = new TagService();
    }

    public function index(): void
    {
        $tags = $this->service->getAll();
        Response::success($tags);
    }

    public function store(): void
    {
        if (!Auth::check()) {
            Response::unauthorized();
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

    public function destroy(int $id): void
    {
        if (!Auth::check()) {
            Response::unauthorized();
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

    private function getJsonInput(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
