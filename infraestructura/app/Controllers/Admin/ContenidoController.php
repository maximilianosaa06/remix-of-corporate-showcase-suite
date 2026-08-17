<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Repositories\ContenidoRepository;

class ContenidoController
{
    private ContenidoRepository $repo;

    public function __construct()
    {
        Auth::requireRole('admin');
        $this->repo = new ContenidoRepository();
    }

    public function index(): void
    {
        $contenido = $this->repo->findByClave('home');
        $pageTitle = 'Contenido Institucional — TECH HUB ULS';
        $success = $_GET['updated'] ?? '';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/contenido.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $contenido = $this->repo->findById($id);

        if (!$contenido) {
            http_response_code(404);
            echo 'Contenido no encontrado.';
            return;
        }

        $sobre_titulo = trim($_POST['sobre_titulo'] ?? '');
        $sobre_texto = trim($_POST['sobre_texto'] ?? '');
        $mision_titulo = trim($_POST['mision_titulo'] ?? '');
        $mision_texto = trim($_POST['mision_texto'] ?? '');
        $vision_titulo = trim($_POST['vision_titulo'] ?? '');
        $vision_texto = trim($_POST['vision_texto'] ?? '');
        $objetivos_titulo = trim($_POST['objetivos_titulo'] ?? '');
        $objetivos_texto = trim($_POST['objetivos_texto'] ?? '');
        $politicas_titulo = trim($_POST['politicas_titulo'] ?? '');
        $politicas_texto = trim($_POST['politicas_texto'] ?? '');

        $errors = [];
        if ($sobre_titulo === '') $errors['sobre_titulo'] = 'El título es obligatorio.';

        if (!empty($errors)) {
            $contenido = $_POST;
            $contenido['id'] = $id;
            $pageTitle = 'Contenido Institucional — TECH HUB ULS';
            $success = '';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/contenido.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $this->repo->update($id, [
            'sobre_titulo'     => $sobre_titulo,
            'sobre_texto'      => $sobre_texto,
            'mision_titulo'    => $mision_titulo,
            'mision_texto'     => $mision_texto,
            'vision_titulo'    => $vision_titulo,
            'vision_texto'     => $vision_texto,
            'objetivos_titulo' => $objetivos_titulo,
            'objetivos_texto'  => $objetivos_texto,
            'politicas_titulo' => $politicas_titulo,
            'politicas_texto'  => $politicas_texto,
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        header('Location: /admin/contenido?updated=1');
        exit;
    }
}
