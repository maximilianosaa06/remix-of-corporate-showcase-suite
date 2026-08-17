<!-- Contacto page -->
<div class="section-default">
    <div class="container" style="max-width: 640px;">
        <h1 class="section-title">Contáctenos</h1>

        <?php if ($success): ?>
            <div style="background: #e8f5e9; border: 1px solid #4caf50; border-radius: var(--radius); padding: 1rem; text-align: center;">
                <p style="color: #2e7d32; font-weight: 600;">Su mensaje ha sido enviado correctamente.</p>
                <p style="color: #2e7d32; font-size: 0.875rem; margin-top: 0.5rem;">Le responderemos a la brevedad posible.</p>
            </div>
        <?php else: ?>

            <?php if (!empty($errors)): ?>
                <div style="background: #fce4ec; border: 1px solid #ef5350; border-radius: var(--radius); padding: 0.75rem; margin-bottom: 1rem;">
                    <p style="color: #c62828; font-weight: 600; font-size: 0.875rem;">Por favor corrija los errores:</p>
                </div>
            <?php endif; ?>

            <form method="POST" action="/contacto" style="margin-top: 1.5rem;">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">

                <div class="form-group">
                    <label class="form-label" for="name">Nombre *</label>
                    <input type="text" id="name" name="name" class="form-input"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                    <?php if (!empty($errors['name'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="subject">Motivo *</label>
                    <select id="subject" name="subject" class="form-select" required>
                        <option value="">Seleccione una opción</option>
                        <option value="Consulta general" <?= ($old['subject'] ?? '') === 'Consulta general' ? 'selected' : '' ?>>Consulta general</option>
                        <option value="Solicitud de servicio" <?= ($old['subject'] ?? '') === 'Solicitud de servicio' ? 'selected' : '' ?>>Solicitud de servicio</option>
                        <option value="Sugerencia" <?= ($old['subject'] ?? '') === 'Sugerencia' ? 'selected' : '' ?>>Sugerencia</option>
                        <option value="Prensa" <?= ($old['subject'] ?? '') === 'Prensa' ? 'selected' : '' ?>>Prensa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" class="form-input"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                    <?php if (!empty($errors['email'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Teléfono</label>
                    <input type="tel" id="phone" name="phone" class="form-input"
                           value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    <?php if (!empty($errors['phone'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['phone']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">Mensaje *</label>
                    <textarea id="message" name="message" class="form-textarea" rows="5" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                    <?php if (!empty($errors['message'])): ?>
                        <p class="form-error"><?= htmlspecialchars($errors['message']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Aviso de privacidad y consentimiento informado -->
                <div style="background:var(--muted);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;margin-bottom:1rem;">
                    <p style="font-size:0.8125rem;font-weight:600;margin-bottom:0.5rem;">Aviso de privacidad y consentimiento informado</p>
                    <p style="font-size:0.75rem;color:var(--muted-foreground);line-height:1.5;margin-bottom:0.75rem;">
                        De conformidad con la Ley N° 19.628 sobre protección de datos personales, le informamos que los datos personales
                        proporcionados en este formulario serán utilizados exclusivamente para dar respuesta a su consulta o solicitud.
                        Sus datos no serán compartidos con terceros y serán almacenados únicamente por el tiempo necesario para atender
                        su requerimiento. Puede ejercer sus derechos de acceso, rectificación y eliminación contactando a
                        <strong>techhub@ulser.cl</strong>.
                    </p>
                    <label class="form-check-label" style="font-size:0.8125rem;">
                        <input type="checkbox" name="privacy_consent" value="1" required
                            <?= (!empty($old['privacy_consent'])) ? 'checked' : '' ?>>
                        He leído y acepto el aviso de privacidad y autorizo el tratamiento de mis datos personales. *
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full">Enviar mensaje</button>
            </form>

        <?php endif; ?>
    </div>
</div>
