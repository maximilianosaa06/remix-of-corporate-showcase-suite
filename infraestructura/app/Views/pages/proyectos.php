<!-- Proyectos page -->
<section class="section-default">
    <div class="container">
        <h1 class="section-title">Proyectos y Servicios</h1>

        <?php if (empty($proyectos)): ?>
            <p class="text-center text-muted-foreground text-sm" style="margin-top: 2rem;">No hay proyectos registrados aún.</p>
        <?php else: ?>
            <div class="grid grid-gap-6 grid-projects">
                <?php foreach ($proyectos as $p): ?>
                    <article class="card">
                        <img src="<?= htmlspecialchars(mediaUrl($p['image'] ?? null, 'proyecto')) ?>"
                             alt="<?= htmlspecialchars($p['name']) ?>"
                             class="card-img card-img-top" loading="lazy" width="800" height="560">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($p['name']) ?></h3>
                            <p><?= htmlspecialchars($p['description'] ?? '') ?></p>
                            <?php if (!empty($p['link'])): ?>
                                <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm" style="margin-top: 1rem;">Ver más</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
