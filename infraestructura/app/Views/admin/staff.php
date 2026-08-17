<!-- Admin Staff — Listado -->
<div class="section-default">
    <div class="container">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">Staff</h1>
            <a href="/admin/staff/crear" class="btn btn-primary">+ Nuevo miembro</a>
        </div>

        <?php if (isset($_GET['created'])): ?>
            <div class="alert alert-success">Miembro creado correctamente.</div>
        <?php elseif (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Miembro actualizado correctamente.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Miembro eliminado.</div>
        <?php endif; ?>

        <?php if (empty($staff)): ?>
            <p class="text-muted-foreground">No hay miembros del staff registrados.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staff as $m): ?>
                            <tr>
                                <td>
                                    <img src="<?= htmlspecialchars(mediaUrl($m['photo'] ?? null, 'staff')) ?>"
                                         alt="" class="admin-thumb-square">
                                </td>
                                <td><?= htmlspecialchars($m['name']) ?></td>
                                <td><?= htmlspecialchars($m['position'] ?? '—') ?></td>
                                <td class="admin-actions">
                                    <a href="/admin/staff/editar?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline">Editar</a>
                                    <form method="POST" action="/admin/staff/eliminar" style="display:inline;"
                                          onsubmit="return confirm('¿Eliminar este miembro del staff?');">
                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">
                                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
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
