<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Services\FooterService;
use App\Core\Auth;
use App\Support\Response;

class FooterApiController
{
    private FooterService $service;

    public function __construct()
    {
        $this->service = new FooterService();
    }

    public function show(): void
    {
        $data = $this->service->getAll();
        Response::success($data);
    }

    public function update(): void
    {
        if (!$this->isAdminOrEditor()) {
            Response::forbidden();
            return;
        }

        $data = $this->getJsonInput();

        try {
            $item = $this->service->updateInfo($data);
            Response::success($item);
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
