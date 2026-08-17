<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Services\AuditService;
use App\Core\Auth;
use App\Support\Response;

class AuditController
{
    private AuditService $service;

    public function __construct()
    {
        $this->service = new AuditService();
    }

    public function index(): void
    {
        if (!$this->isSuperAdmin()) {
            Response::forbidden();
            return;
        }

        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, min(100, (int) ($_GET['per_page'] ?? 50)));

        $data = $this->service->getAll($page, $perPage);
        Response::success($data);
    }

    private function isSuperAdmin(): bool
    {
        if (!Auth::check()) return false;
        return (Auth::user()['role_name'] ?? '') === 'superadmin';
    }
}
