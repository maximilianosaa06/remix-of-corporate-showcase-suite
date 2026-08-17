<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Repositories\ProyectoRepository;
use App\Services\UploadService;

class ProyectosController
{
    private ProyectoRepository $repo;
    private UploadService $upload;

    public function __construct()
    {
        Auth::requireRole('admin');
        $this->repo = new ProyectoRepository();
        $this->upload = new UploadService();
    }

    public function index(): void
    {
        $proyectos = $this->repo->findAll();
        $pageTitle = 'Administrar Proyectos — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/proyectos.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function create(): void
    {
        $proyecto = null;
        $errors = [];
        $old = [];
        $pageTitle = 'Crear Proyecto — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/proyecto_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $active = isset($_POST['active']);

        $errors = [];
        if ($name === '') $errors['name'] = 'El nombre es obligatorio.';

        if (!empty($errors)) {
            $proyecto = null;
            $old = $_POST;
            $old['active'] = $active;
            $pageTitle = 'Crear Proyecto — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/proyecto_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $image = null;
        try {
            $image = $this->upload->upload('image', 'proyectos');
        } catch (\RuntimeException $e) {
            $errors['image'] = $e->getMessage();
            $proyecto = null;
            $old = $_POST;
            $old['active'] = $active;
            $pageTitle = 'Crear Proyecto — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/proyecto_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $this->repo->create([
            'name'        => $name,
            'description' => $description,
            'image'       => $image,
            'link'        => $link,
            'active'      => $active,
        ]);

        header('Location: /admin/proyectos?created=1');
        exit;
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $proyecto = $this->repo->findById($id);

        if (!$proyecto) {
            http_response_code(404);
            echo 'Proyecto no encontrado.';
            return;
        }

        $errors = [];
        $old = $proyecto;
        $pageTitle = 'Editar Proyecto — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/proyecto_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $proyecto = $this->repo->findById($id);

        if (!$proyecto) {
            http_response_code(404);
            echo 'Proyecto no encontrado.';
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $active = isset($_POST['active']);

        $errors = [];
        if ($name === '') $errors['name'] = 'El nombre es obligatorio.';

        if (!empty($errors)) {
            $old = $_POST;
            $old['id'] = $id;
            $old['active'] = $active;
            $pageTitle = 'Editar Proyecto — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/proyecto_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $image = $proyecto['image'];
        try {
            $newImage = $this->upload->upload('image', 'proyectos');
            if ($newImage) {
                $this->upload->delete($proyecto['image']);
                $image = $newImage;
            }
        } catch (\RuntimeException $e) {
            $errors['image'] = $e->getMessage();
            $old = $_POST;
            $old['id'] = $id;
            $old['active'] = $active;
            $pageTitle = 'Editar Proyecto — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/proyecto_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $this->repo->update($id, [
            'name'        => $name,
            'description' => $description,
            'image'       => $image,
            'link'        => $link,
            'active'      => $active,
        ]);

        header('Location: /admin/proyectos?updated=1');
        exit;
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $proyecto = $this->repo->findById($id);

        if ($proyecto) {
            $this->upload->delete($proyecto['image']);
            $this->repo->delete($id);
        }

        header('Location: /admin/proyectos?deleted=1');
        exit;
    }

    public function toggleActive(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->repo->toggleActive($id);
        header('Location: /admin/proyectos?toggled=1');
        exit;
    }
}
