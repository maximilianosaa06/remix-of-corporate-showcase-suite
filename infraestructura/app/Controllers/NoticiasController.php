<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\NoticiaRepository;

class NoticiasController
{
    public function index(): void
    {
        $repo = new NoticiaRepository();
        $noticias = $repo->findPublishedAll();

        $pageTitle       = 'Noticias — TECH HUB ULS';
        $pageDescription = 'Noticias del Software Factory Lab de la Universidad de La Serena.';

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require dirname(__DIR__) . '/Views/pages/noticias.php';
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }
}
