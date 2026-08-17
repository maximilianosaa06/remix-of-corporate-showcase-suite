<!-- Admin Proyectos — Listado -->
<div class="section-default">
    <div class="container">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">Proyectos</h1>
            <a href="/admin/proyectos/crear" class="btn btn-primary">+ Nuevo proyecto</a>
        </div>

        <?php if (isset($_GET['created'])): ?>
            <div class="alert alert-success">Proyecto creado correctamente.</div>
        <?php elseif (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Proyecto actualizado correctamente.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Proyecto eliminado.</div>
        <?php elseif (isset($_GET['toggled'])): ?>
            <div class="alert alert-success">Estado del proyecto actualizado.</div>
        <?php endif; ?>

        <?php if (empty($proyectos)): ?>
            <p class="text-muted-foreground">No hay proyectos registrados.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proyectos as $p): ?>
                            <tr>
                                <td>
                                    <img src="<?= htmlspecialchars(mediaUrl($p['image'] ?? null, 'proyecto')) ?>"
                                         alt="" class="admin-thumb">
                                </td>
                                <td><?= htmlspecialchars($p['name']) ?></td>
                                <td>
                                    <span class="badge <?= $p['active'] ? 'badge-success' : 'badge-muted' ?>">
                                        <?= $p['active'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="admin-actions">
                                    <a href="/admin/proyectos/editar?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline">Editar</a>
                                    <form method="POST" action="/admin/proyectos/toggle" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline">
                                            <?= $p['active'] ? 'Desactivar' : 'Activar' ?>
                                        </button>
                                    </form>
                                    <form method="POST" action="/admin/proyectos/eliminar" style="display:inline;"
                                          onsubmit="return confirm('¿Eliminar este proyecto?');">
                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-destructive">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div style="margin-top: 1.5rem;">
            <a href="/admin" class="btn btn-outline">Volver al panel</a>
        </div>
    </div>
</div>
