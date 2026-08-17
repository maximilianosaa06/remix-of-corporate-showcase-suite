<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\StaffRepository;

class StaffController
{
    public function index(): void
    {
        $repo = new StaffRepository();
        $staff = $repo->findAll();

        $pageTitle       = 'Staff — TECH HUB ULS';
        $pageDescription = 'Equipo del Software Factory Lab de la Universidad de La Serena.';

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require dirname(__DIR__) . '/Views/pages/staff.php';
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }
}
