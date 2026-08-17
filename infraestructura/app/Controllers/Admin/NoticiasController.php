<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Repositories\NoticiaRepository;
use App\Services\UploadService;
use App\Services\AuditService;

class NoticiasController
{
    private NoticiaRepository $repo;
    private UploadService $upload;
    private AuditService $audit;
    private ?array $user;

    public function __construct()
    {
        Auth::requireRole('admin', 'editor', 'redactor');
        $this->user = Auth::user();
        $this->repo = new NoticiaRepository();
        $this->upload = new UploadService();
        $this->audit = new AuditService();
        $this->repo->ensureStatuses();
    }

    public function index(): void
    {
        $noticias = $this->repo->findAllForAdmin(
            $this->user['id'],
            $this->user['role_name']
        );
        $pageTitle = 'Administrar Noticias — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/noticias.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function create(): void
    {
        $noticia = null;
        $errors = [];
        $old = [];
        $pageTitle = 'Crear Noticia — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/noticia_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function store(): void
    {
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $content = trim($_POST['content'] ?? '');

        $errors = [];
        if ($title === '') $errors['title'] = 'El título es obligatorio.';
        if ($content === '') $errors['content'] = 'El contenido es obligatorio.';

        if (!empty($errors)) {
            $noticia = null;
            $old = $_POST;
            $pageTitle = 'Crear Noticia — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/noticia_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $image = null;
        try {
            $image = $this->upload->upload('image', 'noticias');
        } catch (\RuntimeException $e) {
            $errors['image'] = $e->getMessage();
            $noticia = null;
            $old = $_POST;
            $pageTitle = 'Crear Noticia — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/noticia_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $statusId = $this->repo->getStatusId('pendiente');

        $noticiaId = $this->repo->create([
            'title'       => $title,
            'subtitle'    => $subtitle,
            'content'     => $content,
            'image'       => $image,
            'author_id'   => $this->user['id'],
            'status_id'   => $statusId,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->audit->log('news_create', 'news', $noticiaId, $title);

        header('Location: /admin/noticias?created=1');
        exit;
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $noticia = $this->repo->findById($id);

        if (!$noticia) {
            http_response_code(404);
            echo 'Noticia no encontrada.';
            return;
        }

        if ($this->user['role_name'] === 'redactor' && (int) $noticia['author_id'] !== $this->user['id']) {
            http_response_code(403);
            echo 'No tiene permiso para editar esta noticia.';
            return;
        }

        $errors = [];
        $old = $noticia;
        $pageTitle = 'Editar Noticia — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/noticia_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $noticia = $this->repo->findById($id);

        if (!$noticia) {
            http_response_code(404);
            echo 'Noticia no encontrada.';
            return;
        }

        if ($this->user['role_name'] === 'redactor' && (int) $noticia['author_id'] !== $this->user['id']) {
            http_response_code(403);
            echo 'No tiene permiso para editar esta noticia.';
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $content = trim($_POST['content'] ?? '');

        $errors = [];
        if ($title === '') $errors['title'] = 'El título es obligatorio.';
        if ($content === '') $errors['content'] = 'El contenido es obligatorio.';

        if (!empty($errors)) {
            $old = $_POST;
            $old['id'] = $id;
            $pageTitle = 'Editar Noticia — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/noticia_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $image = $noticia['image'];
        try {
            $newImage = $this->upload->upload('image', 'noticias');
            if ($newImage) {
                $this->upload->delete($noticia['image']);
                $image = $newImage;
            }
        } catch (\RuntimeException $e) {
            $errors['image'] = $e->getMessage();
            $old = $_POST;
            $old['id'] = $id;
            $pageTitle = 'Editar Noticia — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/noticia_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $updateData = [
            'title'    => $title,
            'subtitle' => $subtitle,
            'content'  => $content,
            'image'    => $image,
        ];

        if (in_array($this->user['role_name'], ['admin', 'editor']) && isset($_POST['status'])) {
            $newStatus = $_POST['status'];
            $statusId = $this->repo->getStatusId($newStatus);
            if ($statusId) {
                $updateData['status_id'] = $statusId;
                if ($newStatus === 'publicada' && empty($noticia['publication_date'])) {
                    $updateData['publication_date'] = date('Y-m-d H:i:s');
                }
            }
        }

        $updateData['updated_at'] = date('Y-m-d H:i:s');
        $this->repo->update($id, $updateData);

        $this->audit->log('news_update', 'news', $id, $title);

        header('Location: /admin/noticias?updated=1');
        exit;
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $noticia = $this->repo->findById($id);

        if (!$noticia) {
            header('Location: /admin/noticias');
            exit;
        }

        if ($this->user['role_name'] === 'redactor' && (int) $noticia['author_id'] !== $this->user['id']) {
            header('Location: /admin/noticias?denied=1');
            exit;
        }

        if ($this->user['role_name'] !== 'admin') {
            header('Location: /admin/noticias?denied=1');
            exit;
        }

        $this->audit->log('news_delete', 'news', $id, $noticia['title']);

        $this->upload->delete($noticia['image']);
        $this->repo->delete($id);

        header('Location: /admin/noticias?deleted=1');
        exit;
    }

    public function changeStatus(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $newStatus = $_POST['status'] ?? '';
        $noticia = $this->repo->findById($id);

        if (!$noticia) {
            header('Location: /admin/noticias');
            exit;
        }

        if (!in_array($this->user['role_name'], ['admin', 'editor'])) {
            header('Location: /admin/noticias?denied=1');
            exit;
        }

        $statusId = $this->repo->getStatusId($newStatus);
        if ($statusId) {
            $updateData = ['status_id' => $statusId];
            if ($newStatus === 'publicada' && empty($noticia['publication_date'])) {
                $updateData['publication_date'] = date('Y-m-d H:i:s');
            }
            $this->repo->update($id, $updateData);

            $this->audit->log('news_status_change', 'news', $id, json_encode([
                'old_status' => $noticia['status_name'] ?? 'desconocido',
                'new_status' => $newStatus,
                'title'      => $noticia['title'],
            ], JSON_UNESCAPED_UNICODE));
        }

        header('Location: /admin/noticias?status_changed=1');
        exit;
    }
}
