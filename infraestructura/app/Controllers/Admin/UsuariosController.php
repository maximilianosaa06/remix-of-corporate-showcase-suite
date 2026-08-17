<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Repositories\UserRepository;
use App\Services\AuditService;

class UsuariosController
{
    private UserRepository $repo;
    private AuditService $audit;

    public function __construct()
    {
        Auth::requireRole('admin');
        $this->repo = new UserRepository();
        $this->audit = new AuditService();
    }

    public function index(): void
    {
        $usuarios = $this->repo->findAll();
        $roles = $this->repo->findAllRoles();
        $pageTitle = 'Administrar Usuarios — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/usuarios.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function create(): void
    {
        $usuario = null;
        $roles = $this->repo->findAllRoles();
        $errors = [];
        $old = [];
        $tempPassword = '';
        $pageTitle = 'Crear Usuario — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/usuario_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function store(): void
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role_id = (int) ($_POST['role_id'] ?? 0);
        $active = isset($_POST['active']);

        $errors = [];
        if ($username === '') $errors['username'] = 'El nombre de usuario es obligatorio.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Ingrese un correo válido.';
        if ($role_id <= 0) $errors['role_id'] = 'Seleccione un rol.';

        if (!$errors) {
            $existing = $this->repo->findByEmail($email);
            if ($existing) $errors['email'] = 'Ya existe un usuario con ese correo.';
        }

        $roles = $this->repo->findAllRoles();

        if (!empty($errors)) {
            $usuario = null;
            $old = $_POST;
            $old['active'] = $active;
            $tempPassword = '';
            $pageTitle = 'Crear Usuario — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/usuario_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $tempPassword = self::generateTempPassword(10);
        $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        $newUserId = $this->repo->create([
            'role_id'              => $role_id,
            'username'             => $username,
            'email'                => $email,
            'password'             => $hashedPassword,
            'active'               => $active,
            'must_change_password' => true,
        ]);

        $this->audit->log('user_create', 'app_user', $newUserId, json_encode([
            'username' => $username,
            'email'    => $email,
            'role_id'  => $role_id,
        ], JSON_UNESCAPED_UNICODE));

        $usuario = null;
        $successMessage = "Usuario creado exitosamente. Contraseña temporal: {$tempPassword}";
        $pageTitle = 'Crear Usuario — TECH HUB ULS';
        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/usuario_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $usuario = $this->repo->findById($id);

        if (!$usuario) {
            http_response_code(404);
            echo 'Usuario no encontrado.';
            return;
        }

        $roles = $this->repo->findAllRoles();
        $errors = [];
        $old = $usuario;
        $tempPassword = '';
        $pageTitle = 'Editar Usuario — TECH HUB ULS';

        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/usuario_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $usuario = $this->repo->findById($id);

        if (!$usuario) {
            http_response_code(404);
            echo 'Usuario no encontrado.';
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role_id = (int) ($_POST['role_id'] ?? 0);
        $active = isset($_POST['active']);
        $reset_password = isset($_POST['reset_password']);

        $errors = [];
        if ($username === '') $errors['username'] = 'El nombre de usuario es obligatorio.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Ingrese un correo válido.';
        if ($role_id <= 0) $errors['role_id'] = 'Seleccione un rol.';

        if (!$errors) {
            $existing = $this->repo->findByEmail($email);
            if ($existing && (int) $existing['id'] !== $id) {
                $errors['email'] = 'Ya existe otro usuario con ese correo.';
            }
        }

        $roles = $this->repo->findAllRoles();

        if (!empty($errors)) {
            $old = $_POST;
            $old['id'] = $id;
            $old['active'] = $active;
            $tempPassword = '';
            $pageTitle = 'Editar Usuario — TECH HUB ULS';
            require dirname(__DIR__, 3) . '/Views/layouts/header.php';
            require dirname(__DIR__, 3) . '/Views/admin/usuario_form.php';
            require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
            return;
        }

        $updateData = [
            'username' => $username,
            'email'    => $email,
            'role_id'  => $role_id,
            'active'   => $active,
        ];

        $tempPassword = '';
        if ($reset_password) {
            $tempPassword = self::generateTempPassword(10);
            $updateData['password'] = password_hash($tempPassword, PASSWORD_DEFAULT);
            $updateData['must_change_password'] = true;
        }

        $this->repo->update($id, $updateData);

        $auditDetails = json_encode([
            'username' => $username,
            'email'    => $email,
            'role_id'  => $role_id,
            'active'   => $active,
        ], JSON_UNESCAPED_UNICODE);
        if ($reset_password) {
            $auditDetails = 'password_reset,' . $auditDetails;
        }
        $this->audit->log('user_update', 'app_user', $id, $auditDetails);

        $usuario = $this->repo->findById($id);
        $old = $usuario;
        $pageTitle = 'Editar Usuario — TECH HUB ULS';
        if ($tempPassword) {
            $successMessage = "Contraseña restablecida: {$tempPassword}";
        } else {
            $successMessage = 'Usuario actualizado.';
        }
        require dirname(__DIR__, 3) . '/Views/layouts/header.php';
        require dirname(__DIR__, 3) . '/Views/admin/usuario_form.php';
        require dirname(__DIR__, 3) . '/Views/layouts/footer.php';
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $currentUser = Auth::user();

        if ((int) $currentUser['id'] === $id) {
            header('Location: /admin/usuarios?error=self');
            exit;
        }

        $usuario = $this->repo->findById($id);
        $this->audit->log('user_delete', 'app_user', $id, $usuario ? $usuario['email'] : '');

        $this->repo->delete($id);
        header('Location: /admin/usuarios?deleted=1');
        exit;
    }

    public function toggleActive(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $currentUser = Auth::user();

        if ((int) $currentUser['id'] === $id) {
            header('Location: /admin/usuarios?error=self');
            exit;
        }

        $usuario = $this->repo->findById($id);
        $this->audit->log('user_toggle_active', 'app_user', $id, json_encode([
            'email'  => $usuario['email'] ?? '',
            'active' => !$usuario['active'],
        ], JSON_UNESCAPED_UNICODE));

        $this->repo->toggleActive($id);
        header('Location: /admin/usuarios?toggled=1');
        exit;
    }

    private static function generateTempPassword(int $length = 10): string
    {
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789!@#$%';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
}
