<!-- Admin Noticias — Listado -->
<div class="section-default">
    <div class="container">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">Noticias</h1>
            <a href="/admin/noticias/crear" class="btn btn-primary">+ Nueva noticia</a>
        </div>

        <?php if (isset($_GET['created'])): ?>
            <div class="alert alert-success">Noticia creada correctamente (estado: Pendiente).</div>
        <?php elseif (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Noticia actualizada correctamente.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Noticia eliminada.</div>
        <?php elseif (isset($_GET['status_changed'])): ?>
            <div class="alert alert-success">Estado de la noticia actualizado.</div>
        <?php elseif (isset($_GET['denied'])): ?>
            <div class="alert alert-error">No tiene permisos para realizar esta acción.</div>
        <?php endif; ?>

        <?php if ($this->user['role_name'] === 'redactor'): ?>
            <p class="text-muted-foreground text-sm" style="margin-bottom:1rem;">
                Mostrando solo sus propias noticias.
            </p>
        <?php endif; ?>

        <?php if (empty($noticias)): ?>
            <p class="text-muted-foreground">No hay noticias registradas.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Estado</th>
                            <th>Creada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($noticias as $n): ?>
                            <tr>
                                <td>
                                    <img src="<?= htmlspecialchars(mediaUrl($n['image'] ?? null, 'noticia')) ?>"
                                         alt="" class="admin-thumb">
                                </td>
                                <td><?= htmlspecialchars($n['title']) ?></td>
                                <td><?= htmlspecialchars($n['author_name'] ?? '—') ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($n['status_name'] ?? '') {
                                        'publicada' => 'badge-success',
                                        'pendiente' => 'badge-warning',
                                        'archivada' => 'badge-muted',
                                        default     => 'badge-muted',
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= htmlspecialchars(ucfirst($n['status_name'] ?? '')) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($n['created_at'])) ?></td>
                                <td class="admin-actions">
                                    <a href="/admin/noticias/editar?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline">Editar</a>

                                    <?php if (in_array($this->user['role_name'], ['admin', 'editor'])): ?>
                                        <?php if (($n['status_name'] ?? '') !== 'publicada'): ?>
                                            <form method="POST" action="/admin/noticias/cambiar-estado" style="display:inline;">
                                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">
                                                <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                                <input type="hidden" name="status" value="publicada">
                                                <button type="submit" class="btn btn-sm btn-primary">Publicar</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (($n['status_name'] ?? '') !== 'archivada'): ?>
                                            <form method="POST" action="/admin/noticias/cambiar-estado" style="display:inline;">
                                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">
                                                <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                                <input type="hidden" name="status" value="archivada">
                                                <button type="submit" class="btn btn-sm btn-outline">Archivar</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if ($this->user['role_name'] === 'admin'): ?>
                                        <form method="POST" action="/admin/noticias/eliminar" style="display:inline;"
                                              onsubmit="return confirm('¿Eliminar esta noticia permanentemente?');">
                                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">
                                            <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-destructive">Eliminar</button>
                                        </form>
                                    <?php endif; ?>
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
