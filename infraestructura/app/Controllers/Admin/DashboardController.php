<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Repositories\ContactoRepository;

class DashboardController
{
    public function index(): void
    {
        Auth::requireRole('admin', 'editor', 'redactor');

        $user = Auth::user();
        $pageTitle = 'Panel de administración — TECH HUB ULS';

        $contactRepo = new ContactoRepository();
        $pendingContacts = $contactRepo->countPending();

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/dashboard.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }
}
