<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ProyectoRepository;

class ProyectosController
{
    public function index(): void
    {
        $repo = new ProyectoRepository();
        $proyectos = $repo->findAll();

        $pageTitle       = 'Proyectos — TECH HUB ULS';
        $pageDescription = 'Proyectos y servicios del Software Factory Lab de la Universidad de La Serena.';

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require dirname(__DIR__) . '/Views/pages/proyectos.php';
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }
}
