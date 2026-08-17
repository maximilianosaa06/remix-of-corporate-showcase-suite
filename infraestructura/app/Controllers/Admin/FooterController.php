<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Repositories\FooterRepository;

class FooterController
{
    private FooterRepository $repo;

    public function __construct()
    {
        Auth::requireRole('admin');
        $this->repo = new FooterRepository();
    }

    public function index(): void
    {
        $enlaces = $this->repo->findAll();
        $footerInfo = $this->repo->findFooterInfo();
        $pageTitle = 'Administrar Footer — TECH HUB ULS';
        $success = $_GET['updated'] ?? '';
        $deleted = $_GET['deleted'] ?? '';
        $created = $_GET['created'] ?? '';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/footer.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function storeLink(): void
    {
        $grupo = trim($_POST['grupo'] ?? '');
        $etiqueta = trim($_POST['etiqueta'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $orden = (int) ($_POST['orden'] ?? 0);

        $errors = [];
        if ($grupo === '') $errors['grupo'] = 'El grupo es obligatorio.';
        if ($etiqueta === '') $errors['etiqueta'] = 'La etiqueta es obligatoria.';
        if ($url === '') $errors['url'] = 'La URL es obligatoria.';

        if (!empty($errors)) {
            $enlaces = $this->repo->findAll();
            $footerInfo = $this->repo->findFooterInfo();
            $pageTitle = 'Administrar Footer — TECH HUB ULS';
            $oldLink = $_POST;
            $pageTitle = 'Administrar Footer — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/footer.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $this->repo->createLink([
            'grupo'   => $grupo,
            'etiqueta' => $etiqueta,
            'url'     => $url,
            'orden'   => $orden,
        ]);

        header('Location: /admin/footer?created=1');
        exit;
    }

    public function deleteLink(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->repo->deleteLink($id);
        header('Location: /admin/footer?deleted=1');
        exit;
    }

    public function updateInfo(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $copyright_text = trim($_POST['copyright_text'] ?? '');
        $social_facebook = trim($_POST['social_facebook'] ?? '');
        $social_linkedin = trim($_POST['social_linkedin'] ?? '');
        $social_twitter = trim($_POST['social_twitter'] ?? '');
        $social_instagram = trim($_POST['social_instagram'] ?? '');
        $social_youtube = trim($_POST['social_youtube'] ?? '');

        $this->repo->updateFooterInfo($id, [
            'email'             => $email,
            'phone'             => $phone,
            'address'           => $address,
            'copyright_text'    => $copyright_text,
            'social_facebook'   => $social_facebook,
            'social_linkedin'   => $social_linkedin,
            'social_twitter'    => $social_twitter,
            'social_instagram'  => $social_instagram,
            'social_youtube'    => $social_youtube,
        ]);

        header('Location: /admin/footer?updated=1');
        exit;
    }
}
