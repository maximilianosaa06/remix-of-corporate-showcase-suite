<!-- Staff page -->
<section class="section-default">
    <div class="container">
        <h1 class="section-title">Staff</h1>

        <?php if (empty($staff)): ?>
            <p class="text-center text-muted-foreground text-sm" style="margin-top: 2rem;">No hay miembros del staff registrados aún.</p>
        <?php else: ?>
            <div class="grid grid-gap-6 grid-staff">
                <?php foreach ($staff as $m): ?>
                    <article class="staff-item">
                        <h3><?= htmlspecialchars($m['name']) ?></h3>
                        <img src="<?= htmlspecialchars(mediaUrl($m['photo'] ?? null, 'staff')) ?>"
                             alt="<?= htmlspecialchars($m['name']) ?>"
                             loading="lazy" width="700" height="700">
                        <p class="staff-position"><?= htmlspecialchars($m['position'] ?? '') ?></p>
                        <p class="staff-desc"><?= htmlspecialchars($m['description'] ?? '') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
