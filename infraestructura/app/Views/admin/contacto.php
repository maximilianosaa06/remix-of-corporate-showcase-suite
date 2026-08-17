<!-- Admin Contacto - Listado -->
<div class="section-default">
    <div class="container">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">Solicitudes de contacto</h1>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">Estado actualizado.</div>
        <?php endif; ?>
        <?php if ($deleted): ?>
            <div class="alert alert-success">Solicitud eliminada.</div>
        <?php endif; ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Motivo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($solicitudes)): ?>
                        <tr><td colspan="7" class="text-muted-foreground">No hay solicitudes de contacto.</td></tr>
                    <?php else: ?>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><?= (int) $s['id'] ?></td>
                                <td><?= e($s['name']) ?></td>
                                <td><?= e($s['email']) ?></td>
                                <td><?= e($s['subject'] ?? '-') ?></td>
                                <td><?= e(date('d/m/Y H:i', strtotime($s['sent_at']))) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match($s['status']) {
                                        'pendiente'  => 'badge-warning',
                                        'en_proceso' => 'badge-info',
                                        'respondida' => 'badge-success',
                                        'cerrada'    => 'badge-muted',
                                        default      => 'badge-muted',
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= e($s['status']) ?></span>
                                </td>
                                <td class="admin-actions">
                                    <a href="/admin/contacto/ver?id=<?= (int) $s['id'] ?>" class="btn btn-outline" style="font-size:0.75rem;padding:0.25rem 0.5rem;">Ver</a>
                                    <form method="post" action="/admin/contacto/estado" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" style="font-size:0.75rem;padding:0.15rem 0.3rem;border-radius:var(--radius);border:1px solid var(--border);">
                                            <option value="pendiente" <?= $s['status'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                            <option value="en_proceso" <?= $s['status'] === 'en_proceso' ? 'selected' : '' ?>>En proceso</option>
                                            <option value="respondida" <?= $s['status'] === 'respondida' ? 'selected' : '' ?>>Respondida</option>
                                            <option value="cerrada" <?= $s['status'] === 'cerrada' ? 'selected' : '' ?>>Cerrada</option>
                                        </select>
                                    </form>
                                    <form method="post" action="/admin/contacto/eliminar" style="display:inline;" onsubmit="return confirm('¿Eliminar esta solicitud?')">
                                        <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
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
