<!-- Admin Proyectos — Formulario crear/editar -->
<div class="section-default">
    <div class="container" style="max-width: 640px;">
        <h1 class="section-title"><?= $proyecto ? 'Editar proyecto' : 'Crear proyecto' ?></h1>

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

        <form method="POST"
              action="<?= $proyecto ? '/admin/proyectos/actualizar' : '/admin/proyectos/crear' ?>"
              enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">

            <?php if ($proyecto): ?>
                <input type="hidden" name="id" value="<?= $proyecto['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="name">Nombre *</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                <?php if (!empty($errors['name'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Descripción</label>
                <textarea id="description" name="description" class="form-textarea" rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="link">Enlace externo</label>
                <input type="url" id="link" name="link" class="form-input"
                       value="<?= htmlspecialchars($old['link'] ?? '') ?>"
                       placeholder="https://...">
            </div>

            <div class="form-group">
                <label class="form-label" for="image">Imagen</label>
                <?php if ($proyecto && !empty($proyecto['image'])): ?>
                    <div style="margin-bottom: 0.5rem;">
                        <img src="<?= htmlspecialchars(mediaUrl($proyecto['image'], 'proyecto')) ?>"
                             alt="Imagen actual" style="max-height:120px; border-radius: var(--radius);">
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
                <p class="text-xs text-muted-foreground" style="margin-top:0.25rem;">JPG, PNG, GIF o WebP. Máximo 5MB.</p>
                <?php if (!empty($errors['image'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['image']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-check-label">
                    <input type="checkbox" name="active" value="1"
                        <?= ($old['active'] ?? ($proyecto['active'] ?? true)) ? 'checked' : '' ?>>
                    Activo (visible en el sitio)
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $proyecto ? 'Guardar cambios' : 'Crear proyecto' ?></button>
                <a href="/admin/proyectos" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
