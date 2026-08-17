<!-- Admin Staff — Formulario crear/editar -->
<div class="section-default">
    <div class="container" style="max-width: 640px;">
        <h1 class="section-title"><?= $member ? 'Editar miembro' : 'Crear miembro' ?></h1>

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
              action="<?= $member ? '/admin/staff/actualizar' : '/admin/staff/crear' ?>"
              enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">

            <?php if ($member): ?>
                <input type="hidden" name="id" value="<?= $member['id'] ?>">
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
                <label class="form-label" for="position">Cargo</label>
                <input type="text" id="position" name="position" class="form-input"
                       value="<?= htmlspecialchars($old['position'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Descripción</label>
                <textarea id="description" name="description" class="form-textarea" rows="3"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="photo">Fotografía</label>
                <?php if ($member && !empty($member['photo'])): ?>
                    <div style="margin-bottom: 0.5rem;">
                        <img src="<?= htmlspecialchars(mediaUrl($member['photo'], 'staff')) ?>"
                             alt="Foto actual" style="max-height:120px; border-radius: var(--radius); object-fit:cover;">
                    </div>
                <?php endif; ?>
                <input type="file" id="photo" name="photo" class="form-input" accept="image/*">
                <p class="text-xs text-muted-foreground" style="margin-top:0.25rem;">JPG, PNG, GIF o WebP. Máximo 5MB.</p>
                <?php if (!empty($errors['photo'])): ?>
                    <p class="form-error"><?= htmlspecialchars($errors['photo']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $member ? 'Guardar cambios' : 'Crear miembro' ?></button>
                <a href="/admin/staff" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
