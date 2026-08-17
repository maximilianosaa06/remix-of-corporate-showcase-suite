<!-- Admin Usuarios -->
<div class="section-default">
    <div class="container">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">Usuarios</h1>
            <a href="/admin/usuarios/crear" class="btn btn-primary">Nuevo usuario</a>
        </div>

        <?php if (isset($_GET['created'])): ?>
            <div class="alert alert-success">Usuario creado. Recuerde comunicar la contraseña temporal al usuario.</div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Usuario actualizado.</div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Usuario eliminado.</div>
        <?php endif; ?>
        <?php if (isset($_GET['toggled'])): ?>
            <div class="alert alert-success">Estado del usuario actualizado.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'self'): ?>
            <div class="alert alert-error">No puede modificar su propia cuenta desde esta interfaz.</div>
        <?php endif; ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Cambiar clave</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr><td colspan="7" class="text-muted-foreground">No hay usuarios registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= (int) $u['id'] ?></td>
                                <td><?= e($u['username']) ?></td>
                                <td><?= e($u['email']) ?></td>
                                <td><span class="badge"><?= e($u['role_name']) ?></span></td>
                                <td>
                                    <?php if ($u['active']): ?>
                                        <span class="badge badge-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-muted">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u['must_change_password']): ?>
                                        <span class="badge badge-warning">Pendiente</span>
                                    <?php else: ?>
                                        <span class="badge badge-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="admin-actions">
                                    <a href="/admin/usuarios/editar?id=<?= (int) $u['id'] ?>" class="btn btn-outline" style="font-size:0.75rem;padding:0.25rem 0.5rem;">Editar</a>
                                    <form method="post" action="/admin/usuarios/toggle" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                        <button type="submit" class="btn <?= $u['active'] ? 'btn-outline' : 'btn-primary' ?>" style="font-size:0.75rem;padding:0.25rem 0.5rem;"><?= $u['active'] ? 'Desactivar' : 'Activar' ?></button>
                                    </form>
                                    <form method="post" action="/admin/usuarios/eliminar" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?')">
                                        <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                        <button type="submit" class="btn btn-outline" style="font-size:0.75rem;padding:0.25rem 0.5rem;border-color:var(--destructive);color:var(--destructive);">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.5rem;">
            <a href="/admin" class="btn btn-outline">Volver al panel</a>
        </div>
    </div>
</div>
