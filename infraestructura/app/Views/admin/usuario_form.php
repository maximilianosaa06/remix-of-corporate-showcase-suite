<!-- Admin Usuario Form -->
<div class="section-default">
    <div class="container">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">
                <?= $usuario ? 'Editar usuario' : 'Crear usuario' ?>
            </h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Corrija los siguientes errores:</strong>
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <strong><?= e($successMessage) ?></strong>
            </div>
        <?php endif; ?>

        <?php if (empty($successMessage)): ?>
        <form method="post" action="/admin/usuarios/<?= $usuario ? 'actualizar' : 'crear' ?>" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">
            <?php if ($usuario): ?>
                <input type="hidden" name="id" value="<?= (int) $usuario['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="username" class="form-label">Nombre de usuario *</label>
                <input type="text" id="username" name="username" class="form-control"
                       value="<?= e($old['username'] ?? '') ?>" required maxlength="50">
                <?php if (!empty($errors['username'])): ?>
                    <span class="text-destructive text-sm"><?= e($errors['username']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico *</label>
                <input type="email" id="email" name="email" class="form-control"
                       value="<?= e($old['email'] ?? '') ?>" required maxlength="150">
                <?php if (!empty($errors['email'])): ?>
                    <span class="text-destructive text-sm"><?= e($errors['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="role_id" class="form-label">Rol *</label>
                <select id="role_id" name="role_id" class="form-control" required>
                    <option value="0">— Seleccionar rol —</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= (int) $r['id'] ?>"
                            <?= ((int) ($old['role_id'] ?? 0) === (int) $r['id']) ? 'selected' : '' ?>>
                            <?= e($r['name']) ?><?= $r['description'] ? ' — ' . e($r['description']) : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['role_id'])): ?>
                    <span class="text-destructive text-sm"><?= e($errors['role_id']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-check-label">
                    <input type="checkbox" name="active" value="1"
                        <?= (!empty($old['active']) || (!isset($old['active']) && !$usuario)) ? 'checked' : '' ?>>
                    Activo
                </label>
            </div>

            <?php if ($usuario): ?>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" name="reset_password" value="1">
                        Restablecer contraseña (genera contraseña temporal)
                    </label>
                    <p class="text-sm text-muted-foreground" style="margin-top:0.25rem;">
                        El usuario deberá cambiar la contraseña en su próximo inicio de sesión.
                    </p>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $usuario ? 'Actualizar' : 'Crear usuario' ?></button>
                <a href="/admin/usuarios" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
        <?php else: ?>
            <div style="margin-top:1rem;">
                <a href="/admin/usuarios" class="btn btn-outline">Volver a la lista</a>
            </div>
        <?php endif; ?>
    </div>
</div>
