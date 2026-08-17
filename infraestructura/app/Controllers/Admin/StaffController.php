<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Repositories\StaffRepository;
use App\Services\UploadService;

class StaffController
{
    private StaffRepository $repo;
    private UploadService $upload;

    public function __construct()
    {
        Auth::requireRole('admin');
        $this->repo = new StaffRepository();
        $this->upload = new UploadService();
    }

    public function index(): void
    {
        $staff = $this->repo->findAll();
        $pageTitle = 'Administrar Staff — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/staff.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function create(): void
    {
        $member = null;
        $errors = [];
        $old = [];
        $pageTitle = 'Crear Miembro — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/staff_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];
        if ($name === '') $errors['name'] = 'El nombre es obligatorio.';

        if (!empty($errors)) {
            $member = null;
            $old = $_POST;
            $pageTitle = 'Crear Miembro — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/staff_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $photo = null;
        try {
            $photo = $this->upload->upload('photo', 'staff');
        } catch (\RuntimeException $e) {
            $errors['photo'] = $e->getMessage();
            $member = null;
            $old = $_POST;
            $pageTitle = 'Crear Miembro — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/staff_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $this->repo->create([
            'name'        => $name,
            'position'    => $position,
            'photo'       => $photo,
            'description' => $description,
        ]);

        header('Location: /admin/staff?created=1');
        exit;
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $member = $this->repo->findById($id);

        if (!$member) {
            http_response_code(404);
            echo 'Miembro no encontrado.';
            return;
        }

        $errors = [];
        $old = $member;
        $pageTitle = 'Editar Miembro — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/staff_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $member = $this->repo->findById($id);

        if (!$member) {
            http_response_code(404);
            echo 'Miembro no encontrado.';
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];
        if ($name === '') $errors['name'] = 'El nombre es obligatorio.';

        if (!empty($errors)) {
            $old = $_POST;
            $old['id'] = $id;
            $pageTitle = 'Editar Miembro — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/staff_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $photo = $member['photo'];
        try {
            $newPhoto = $this->upload->upload('photo', 'staff');
            if ($newPhoto) {
                $this->upload->delete($member['photo']);
                $photo = $newPhoto;
            }
        } catch (\RuntimeException $e) {
            $errors['photo'] = $e->getMessage();
            $old = $_POST;
            $old['id'] = $id;
            $pageTitle = 'Editar Miembro — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/staff_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $this->repo->update($id, [
            'name'        => $name,
            'position'    => $position,
            'photo'       => $photo,
            'description' => $description,
        ]);

        header('Location: /admin/staff?updated=1');
        exit;
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $member = $this->repo->findById($id);

        if ($member) {
            $this->upload->delete($member['photo']);
            $this->repo->delete($id);
        }

        header('Location: /admin/staff?deleted=1');
        exit;
    }
}
