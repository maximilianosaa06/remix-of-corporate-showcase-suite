<!-- Cambiar contraseña obligatoria -->
<div class="section-default">
    <div class="container" style="max-width:500px;">
        <h1 class="section-title">Cambiar contraseña</h1>

        <p class="text-muted-foreground" style="margin-bottom:1.5rem;">
            Debe cambiar su contraseña para continuar. Este es un requisito de seguridad.
        </p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
            <div style="margin-top:1rem;">
                <a href="/admin" class="btn btn-primary">Ir al panel de administración</a>
            </div>
        <?php else: ?>
        <form method="post" action="/cambiar-password" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">

            <div class="form-group">
                <label for="current_password" class="form-label">Contraseña actual</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="new_password" class="form-label">Nueva contraseña</label>
                <input type="password" id="new_password" name="new_password" class="form-control"
                       required minlength="8">
                <p class="text-sm text-muted-foreground" style="margin-top:0.25rem;">Mínimo 8 caracteres.</p>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmar nueva contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>
