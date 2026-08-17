<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ProyectoRepository;
use App\Repositories\StaffRepository;
use App\Repositories\NoticiaRepository;
use App\Repositories\ContenidoRepository;

class HomeController
{
    public function index(): void
    {
        $proyectosRepo = new ProyectoRepository();
        $staffRepo     = new StaffRepository();
        $noticiasRepo  = new NoticiaRepository();
        $contenidoRepo = new ContenidoRepository();

        $proyectos = $proyectosRepo->findAllActive(4);
        $staff     = $staffRepo->findAllActive(4);
        $noticias  = $noticiasRepo->findPublished(3);
        $contenido = $contenidoRepo->findHome();

        $pageTitle       = 'TECH HUB ULS — Software Factory Lab Universidad de La Serena';
        $pageDescription = 'Software Factory Lab de la Universidad de La Serena: proyectos, servicios, staff y noticias del laboratorio de desarrollo de software.';

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require dirname(__DIR__) . '/Views/pages/home.php';
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }
}
