<?php
$canChangeStatus = in_array($this->user['role_name'], ['admin', 'editor']);
$currentStatus = '';
if ($noticia) {
    $db = \App\Core\Database::getInstance();
    $statusRow = $db->fetchOne("SELECT ns.name FROM news n JOIN news_status ns ON n.status_id = ns.id WHERE n.id = :id", ['id' => $noticia['id']]);
    $currentStatus = $statusRow['name'] ?? 'pendiente';
}
?>

<!-- Admin Noticias — Formulario crear/editar -->
<div class="section-default">
    <div class="container" style="max-width: 720px;">
        <h1 class="section-title"><?= $noticia ? 'Editar noticia' : 'Crear noticia' ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <p>Corrija los errores:</p>
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($noticia && $canChangeStatus): ?>
            <div class="alert alert-info">
                <strong>Estado actual:</strong>
                <?php
                $statusClass = match ($currentStatus) {
                    'publicada' => 'badge-success',
                    'pendiente' => 'badge-warning',
                    'archivada' => 'badge-muted',
                    default     => 'badge-muted',
                };
                ?>
                <span class="badge <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($currentStatus)) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST"
              action="<?= $noticia ? '/admin/noticias/actualizar' : '/admin/noticias/crear' ?>"
              enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">

            <?php if ($noticia): ?>
                <input type="hidden" name="id" value="<?= $noticia['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="title">Título *</label>
                <input type="text" id="title" name="title" class="form-input"
                       value="<?= htmlspecialchars($old['title'] ?? '') ?>" required>
                <?php if (!empty($errors['title'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['title']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="subtitle">Subtítulo</label>
                <input type="text" id="subtitle" name="subtitle" class="form-input"
                       value="<?= htmlspecialchars($old['subtitle'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="content">Contenido *</label>
                <textarea id="content" name="content" class="form-textarea" rows="10" required><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
                <p class="text-xs text-muted-foreground" style="margin-top:0.25rem;">Use un párrafo por línea para el formateo.</p>
                <?php if (!empty($errors['content'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['content']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="image">Imagen</label>
                <?php if ($noticia && !empty($noticia['image'])): ?>
                    <div style="margin-bottom: 0.5rem;">
                        <img src="<?= htmlspecialchars(mediaUrl($noticia['image'], 'noticia')) ?>"
                             alt="Imagen actual" style="max-height:150px; border-radius: var(--radius); object-fit:cover;">
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
                <p class="text-xs text-muted-foreground" style="margin-top:0.25rem;">JPG, PNG, GIF o WebP. Máximo 5MB.</p>
                <?php if (!empty($errors['image'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['image']) ?></p>
                <?php endif; ?>
            </div>

            <?php if ($canChangeStatus && $noticia): ?>
                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <div class="form-radio-group">
                        <label class="form-check-label">
                            <input type="radio" name="status" value="pendiente"
                                <?= $currentStatus === 'pendiente' ? 'checked' : '' ?>>
                            Pendiente
                        </label>
                        <label class="form-check-label">
                            <input type="radio" name="status" value="publicada"
                                <?= $currentStatus === 'publicada' ? 'checked' : '' ?>>
                            Publicada
                        </label>
                        <label class="form-check-label">
                            <input type="radio" name="status" value="archivada"
                                <?= $currentStatus === 'archivada' ? 'checked' : '' ?>>
                            Archivada
                        </label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $noticia ? 'Guardar cambios' : 'Crear noticia' ?></button>
                <a href="/admin/noticias" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
