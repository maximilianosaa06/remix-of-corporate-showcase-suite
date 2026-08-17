<!-- Admin Contacto - Ver solicitud -->
<div class="section-default">
    <div class="container" style="max-width:700px;">
        <div class="admin-header">
            <h1 class="section-title" style="margin-bottom:0;">Detalle de solicitud</h1>
        </div>

        <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <p class="text-sm text-muted-foreground" style="margin-bottom:0.125rem;">Nombre</p>
                    <p style="font-weight:600;"><?= e($solicitud['name']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-muted-foreground" style="margin-bottom:0.125rem;">Correo</p>
                    <p style="font-weight:600;"><a href="mailto:<?= e($solicitud['email']) ?>"><?= e($solicitud['email']) ?></a></p>
                </div>
                <?php if ($solicitud['phone']): ?>
                <div>
                    <p class="text-sm text-muted-foreground" style="margin-bottom:0.125rem;">Teléfono</p>
                    <p style="font-weight:600;"><?= e($solicitud['phone']) ?></p>
                </div>
                <?php endif; ?>
                <div>
                    <p class="text-sm text-muted-foreground" style="margin-bottom:0.125rem;">Motivo</p>
                    <p style="font-weight:600;"><?= e($solicitud['subject'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-sm text-muted-foreground" style="margin-bottom:0.125rem;">Fecha de envío</p>
                    <p style="font-weight:600;"><?= e(date('d/m/Y H:i', strtotime($solicitud['sent_at']))) ?></p>
                </div>
                <div>
                    <p class="text-sm text-muted-foreground" style="margin-bottom:0.125rem;">Estado</p>
                    <p style="font-weight:600;"><?= e($solicitud['status']) ?></p>
                </div>
            </div>

            <div style="border-top:1px solid var(--border);padding-top:1rem;">
                <p class="text-sm text-muted-foreground" style="margin-bottom:0.5rem;">Mensaje:</p>
                <div style="background:var(--muted);padding:1rem;border-radius:var(--radius);white-space:pre-wrap;"><?= e($solicitud['message']) ?></div>
            </div>
        </div>

        <div style="margin-top:1.5rem;display:flex;gap:0.75rem;">
            <a href="/admin/contacto" class="btn btn-outline">Volver al listado</a>
            <a href="mailto:<?= e($solicitud['email']) ?>?subject=RE: <?= e($solicitud['subject'] ?? 'Tu consulta TECH HUB ULS') ?>" class="btn btn-primary">Responder por correo</a>
        </div>
    </div>
</div>
