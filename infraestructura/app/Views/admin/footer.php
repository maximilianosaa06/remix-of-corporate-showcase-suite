<!-- Admin Footer -->
<div class="section-default">
    <div class="container">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">Footer del sitio</h1>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">Cambios guardados exitosamente.</div>
        <?php endif; ?>
        <?php if ($deleted): ?>
            <div class="alert alert-success">Enlace eliminado.</div>
        <?php endif; ?>
        <?php if ($created): ?>
            <div class="alert alert-success">Enlace agregado.</div>
        <?php endif; ?>

        <!-- Sección: Información general del footer -->
        <?php if ($footerInfo): ?>
        <fieldset style="border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;">
            <legend style="font-weight:600;padding:0 0.5rem;">Información general</legend>

            <form method="post" action="/admin/footer/info" class="admin-form">
                <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">
                <input type="hidden" name="id" value="<?= (int) $footerInfo['id'] ?>">

                <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label for="email" class="form-label">Correo de contacto</label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="<?= e($footerInfo['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" id="phone" name="phone" class="form-control"
                               value="<?= e($footerInfo['phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Dirección</label>
                    <input type="text" id="address" name="address" class="form-control"
                           value="<?= e($footerInfo['address'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="copyright_text" class="form-label">Texto de derechos de autor</label>
                    <input type="text" id="copyright_text" name="copyright_text" class="form-control"
                           value="<?= e($footerInfo['copyright_text'] ?? '') ?>"
                           placeholder="TECH HUB ULS. Todos los derechos reservados.">
                </div>

                <h3 style="font-size:1rem;margin:1rem 0 0.5rem;">Redes sociales</h3>
                <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label for="social_facebook" class="form-label">Facebook URL</label>
                        <input type="url" id="social_facebook" name="social_facebook" class="form-control"
                               value="<?= e($footerInfo['social_facebook'] ?? '') ?>" placeholder="https://facebook.com/...">
                    </div>
                    <div class="form-group">
                        <label for="social_linkedin" class="form-label">LinkedIn URL</label>
                        <input type="url" id="social_linkedin" name="social_linkedin" class="form-control"
                               value="<?= e($footerInfo['social_linkedin'] ?? '') ?>" placeholder="https://linkedin.com/...">
                    </div>
                    <div class="form-group">
                        <label for="social_twitter" class="form-label">Twitter/X URL</label>
                        <input type="url" id="social_twitter" name="social_twitter" class="form-control"
                               value="<?= e($footerInfo['social_twitter'] ?? '') ?>" placeholder="https://x.com/...">
                    </div>
                    <div class="form-group">
                        <label for="social_instagram" class="form-label">Instagram URL</label>
                        <input type="url" id="social_instagram" name="social_instagram" class="form-control"
                               value="<?= e($footerInfo['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/...">
                    </div>
                    <div class="form-group">
                        <label for="social_youtube" class="form-label">YouTube URL</label>
                        <input type="url" id="social_youtube" name="social_youtube" class="form-control"
                               value="<?= e($footerInfo['social_youtube'] ?? '') ?>" placeholder="https://youtube.com/...">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar información</button>
                </div>
            </form>
        </fieldset>
        <?php endif; ?>

        <!-- Sección: Enlaces del footer -->
        <fieldset style="border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;">
            <legend style="font-weight:600;padding:0 0.5rem;">Enlaces del footer</legend>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Grupo</th>
                            <th>Etiqueta</th>
                            <th>URL</th>
                            <th>Orden</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($enlaces)): ?>
                            <tr><td colspan="6" class="text-muted-foreground">No hay enlaces.</td></tr>
                        <?php else: ?>
                            <?php foreach ($enlaces as $enlace): ?>
                                <tr>
                                    <td><?= (int) $enlace['id'] ?></td>
                                    <td><?= e($enlace['grupo']) ?></td>
                                    <td><?= e($enlace['etiqueta']) ?></td>
                                    <td><?= e($enlace['url']) ?></td>
                                    <td><?= (int) $enlace['orden'] ?></td>
                                    <td class="admin-actions">
                                        <form method="post" action="/admin/footer/eliminar" style="display:inline;" onsubmit="return confirm('¿Eliminar este enlace?')">
                                            <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">
                                            <input type="hidden" name="id" value="<?= (int) $enlace['id'] ?>">
                                            <button type="submit" class="btn btn-outline" style="font-size:0.75rem;padding:0.25rem 0.5rem;border-color:var(--destructive);color:var(--destructive);">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Formulario agregar enlace -->
            <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);">
                <h3 style="font-size:1rem;margin-bottom:0.75rem;">Agregar enlace</h3>
                <form method="post" action="/admin/footer/crear" class="admin-form" style="max-width:100%;">
                    <input type="hidden" name="csrf_token" value="<?= e(Auth::generateCsrfToken()) ?>">

                    <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr 2fr 1fr auto;gap:0.75rem;align-items:end;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="grupo" class="form-label">Grupo</label>
                            <input type="text" id="grupo" name="grupo" class="form-control"
                                   value="<?= e($oldLink['grupo'] ?? '') ?>" placeholder="Ej: Sitio" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="etiqueta" class="form-label">Etiqueta</label>
                            <input type="text" id="etiqueta" name="etiqueta" class="form-control"
                                   value="<?= e($oldLink['etiqueta'] ?? '') ?>" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="url" class="form-label">URL</label>
                            <input type="text" id="url" name="url" class="form-control"
                                   value="<?= e($oldLink['url'] ?? '') ?>" placeholder="/ruta o https://..." required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="orden" class="form-label">Orden</label>
                            <input type="number" id="orden" name="orden" class="form-control"
                                   value="<?= (int) ($oldLink['orden'] ?? 0) ?>" min="0" style="width:70px;">
                        </div>
                        <button type="submit" class="btn btn-primary" style="height:fit-content;">Agregar</button>
                    </div>
                </form>
            </div>
        </fieldset>

        <div style="margin-top:1.5rem;">
            <a href="/admin" class="btn btn-outline">Volver al panel</a>
        </div>
    </div>
</div>
