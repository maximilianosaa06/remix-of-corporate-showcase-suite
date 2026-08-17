<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Repositories\ContactoRepository;
use App\Services\AuditService;

class AdminContactoController
{
    private ContactoRepository $repo;
    private AuditService $audit;

    public function __construct()
    {
        Auth::requireRole('admin', 'editor');
        $this->repo = new ContactoRepository();
        $this->audit = new AuditService();
    }

    public function index(): void
    {
        $solicitudes = $this->repo->findAll();
        $pageTitle = 'Solicitudes de contacto — TECH HUB ULS';
        $success = $_GET['updated'] ?? '';
        $deleted = $_GET['deleted'] ?? '';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/contacto.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function view(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $solicitud = $this->repo->findById($id);

        if (!$solicitud) {
            http_response_code(404);
            echo 'Solicitud no encontrada.';
            return;
        }

        $pageTitle = 'Ver solicitud — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/contacto_view.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function updateStatus(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        $validStatuses = ['pendiente', 'en_proceso', 'respondida', 'cerrada'];
        if (!in_array($status, $validStatuses, true)) {
            header('Location: /admin/contacto');
            exit;
        }

        $solicitud = $this->repo->findById($id);
        if (!$solicitud) {
            header('Location: /admin/contacto');
            exit;
        }

        $oldStatus = $solicitud['status'];
        $this->repo->updateStatus($id, $status);

        $this->audit->log(
            'contact_status_change',
            'contact_request',
            $id,
            json_encode(['old_status' => $oldStatus, 'new_status' => $status], JSON_UNESCAPED_UNICODE)
        );

        header('Location: /admin/contacto?updated=1');
        exit;
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $solicitud = $this->repo->findById($id);

        if ($solicitud) {
            $this->repo->delete($id);
            $this->audit->log('contact_delete', 'contact_request', $id, $solicitud['email']);
        }

        header('Location: /admin/contacto?deleted=1');
        exit;
    }
}
