<!-- Admin Contenido Institucional -->
<div class="section-default">
    <div class="container">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">Contenido Institucional</h1>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">Contenido actualizado exitosamente.</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Corrija los errores:</strong>
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($contenido): ?>
        <form method="post" action="/admin/contenido/actualizar" class="admin-form">
            <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">
            <input type="hidden" name="id" value="<?= (int) $contenido['id'] ?>">

            <!-- Sobre nosotros -->
            <fieldset style="border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;">
                <legend style="font-weight:600;padding:0 0.5rem;">Sobre nosotros</legend>

                <div class="form-group">
                    <label for="sobre_titulo" class="form-label">Título</label>
                    <input type="text" id="sobre_titulo" name="sobre_titulo" class="form-control"
                           value="<?= e($contenido['sobre_titulo'] ?? '') ?>" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="sobre_texto" class="form-label">Texto descriptivo</label>
                    <textarea id="sobre_texto" name="sobre_texto" class="form-control" rows="4"><?= e($contenido['sobre_texto'] ?? '') ?></textarea>
                </div>
            </fieldset>

            <!-- Misión -->
            <fieldset style="border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;">
                <legend style="font-weight:600;padding:0 0.5rem;">Misión</legend>

                <div class="form-group">
                    <label for="mision_titulo" class="form-label">Título</label>
                    <input type="text" id="mision_titulo" name="mision_titulo" class="form-control"
                           value="<?= e($contenido['mision_titulo'] ?? '') ?>" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="mision_texto" class="form-label">Texto</label>
                    <textarea id="mision_texto" name="mision_texto" class="form-control" rows="4"><?= e($contenido['mision_texto'] ?? '') ?></textarea>
                </div>
            </fieldset>

            <!-- Visión -->
            <fieldset style="border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;">
                <legend style="font-weight:600;padding:0 0.5rem;">Visión</legend>

                <div class="form-group">
                    <label for="vision_titulo" class="form-label">Título</label>
                    <input type="text" id="vision_titulo" name="vision_titulo" class="form-control"
                           value="<?= e($contenido['vision_titulo'] ?? '') ?>" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="vision_texto" class="form-label">Texto</label>
                    <textarea id="vision_texto" name="vision_texto" class="form-control" rows="4"><?= e($contenido['vision_texto'] ?? '') ?></textarea>
                </div>
            </fieldset>

            <!-- Objetivos -->
            <fieldset style="border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;">
                <legend style="font-weight:600;padding:0 0.5rem;">Objetivos</legend>

                <div class="form-group">
                    <label for="objetivos_titulo" class="form-label">Título</label>
                    <input type="text" id="objetivos_titulo" name="objetivos_titulo" class="form-control"
                           value="<?= e($contenido['objetivos_titulo'] ?? '') ?>" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="objetivos_texto" class="form-label">Texto</label>
                    <textarea id="objetivos_texto" name="objetivos_texto" class="form-control" rows="4"><?= e($contenido['objetivos_texto'] ?? '') ?></textarea>
                </div>
            </fieldset>

            <!-- Políticas -->
            <fieldset style="border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;">
                <legend style="font-weight:600;padding:0 0.5rem;">Políticas</legend>

                <div class="form-group">
                    <label for="politicas_titulo" class="form-label">Título</label>
                    <input type="text" id="politicas_titulo" name="politicas_titulo" class="form-control"
                           value="<?= e($contenido['politicas_titulo'] ?? '') ?>" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="politicas_texto" class="form-label">Texto</label>
                    <textarea id="politicas_texto" name="politicas_texto" class="form-control" rows="4"><?= e($contenido['politicas_texto'] ?? '') ?></textarea>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="/admin" class="btn btn-outline">Volver al panel</a>
            </div>
        </form>
        <?php else: ?>
            <div class="alert alert-info">No se encontró contenido institucional. Asegúrese de que la tabla contenido_sitio tenga datos.</div>
        <?php endif; ?>
    </div>
</div>
