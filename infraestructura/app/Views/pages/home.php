<?php
/**
 * Landing page — Home
 * Variables: $proyectos, $staff, $noticias, $contenido
 */
?>

<!-- Hero / Sobre nosotros -->
<section class="section-hero">
    <div class="section-hero-inner">
        <img src="/assets/images/logo-sfl-color.svg" alt="TECH HUB ULS" width="260" height="86" style="margin: 0 auto; height: 80px; width: auto;">

        <h1><?= htmlspecialchars($contenido['sobre_titulo'] ?? 'Sobre nosotros') ?></h1>
        <p><?= nl2br(htmlspecialchars($contenido['sobre_texto'] ?? '')) ?></p>

        <?php if (!empty($contenido['mision_titulo']) || !empty($contenido['mision_texto'])): ?>
        <div style="margin-top:2rem;">
            <h2><?= htmlspecialchars($contenido['mision_titulo'] ?? 'Misión') ?></h2>
            <p><?= nl2br(htmlspecialchars($contenido['mision_texto'] ?? '')) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($contenido['vision_titulo']) || !empty($contenido['vision_texto'])): ?>
        <div style="margin-top:1.5rem;">
            <h2><?= htmlspecialchars($contenido['vision_titulo'] ?? 'Visión') ?></h2>
            <p><?= nl2br(htmlspecialchars($contenido['vision_texto'] ?? '')) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($contenido['objetivos_titulo']) || !empty($contenido['objetivos_texto'])): ?>
        <div style="margin-top:1.5rem;">
            <h2><?= htmlspecialchars($contenido['objetivos_titulo'] ?? 'Objetivos') ?></h2>
            <p><?= nl2br(htmlspecialchars($contenido['objetivos_texto'] ?? '')) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($contenido['politicas_titulo']) || !empty($contenido['politicas_texto'])): ?>
        <div style="margin-top:1.5rem;">
            <h2><?= htmlspecialchars($contenido['politicas_titulo'] ?? 'Políticas') ?></h2>
            <p><?= nl2br(htmlspecialchars($contenido['politicas_texto'] ?? '')) ?></p>
        </div>
        <?php endif; ?>

        <a href="/contacto" class="btn btn-destructive" style="margin-top: 2rem;">Contáctenos</a>
    </div>
</section>

<!-- Proyectos -->
<section class="section-surface">
    <div class="container">
        <h2 class="section-title">Proyectos de TECH HUB</h2>
        <div class="grid grid-gap-6 grid-projects">
            <?php foreach ($proyectos as $p): ?>
                <article class="card">
                    <img src="<?= htmlspecialchars(mediaUrl($p['image'] ?? null, 'proyecto')) ?>"
                         alt="<?= htmlspecialchars($p['name']) ?>"
                         class="card-img card-img-top" loading="lazy" width="800" height="560">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <p><?= htmlspecialchars($p['description'] ?? '') ?></p>
                        <a href="/proyectos" class="btn btn-destructive btn-sm" style="margin-top: 1rem;">Ver más</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if (empty($proyectos)): ?>
            <p class="text-center text-muted-foreground text-sm" style="margin-top: 2rem;">No hay proyectos registrados aún.</p>
        <?php endif; ?>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="/proyectos" class="btn btn-destructive">Ver todos los proyectos</a>
        </div>
    </div>
</section>

<!-- Staff -->
<section class="section-default">
    <div class="container">
        <div class="section-rule">
            <h2 class="section-title">Conoce al Staff</h2>
        </div>
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
        <?php if (empty($staff)): ?>
            <p class="text-center text-muted-foreground text-sm" style="margin-top: 2rem;">No hay miembros del staff registrados aún.</p>
        <?php endif; ?>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="/staff" class="btn btn-destructive">Ver todos los miembros</a>
        </div>
    </div>
</section>

<!-- Noticias -->
<section class="section-surface">
    <div class="container">
        <div class="section-rule" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
            <h2 class="section-title" style="margin-bottom: 0;">Noticias</h2>
            <a href="/noticias" class="btn btn-destructive btn-sm">Ver más</a>
        </div>
        <div class="grid grid-gap-6 grid-news" style="margin-top: 2rem;">
            <?php foreach ($noticias as $n): ?>
                <article class="card">
                    <img src="<?= htmlspecialchars(mediaUrl($n['image'] ?? null, 'noticia')) ?>"
                         alt="<?= htmlspecialchars($n['title']) ?>"
                         class="card-img card-img-wide" loading="lazy" width="800" height="560">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($n['title']) ?></h3>
                        <p class="text-xs text-muted-foreground" style="margin-top: 0.25rem;">
                            por: <?= htmlspecialchars($n['author_name'] ?? 'redactor') ?>
                        </p>
                        <p><?= htmlspecialchars($n['subtitle'] ?? mb_strimwidth($n['content'] ?? '', 0, 120, '...')) ?></p>
                        <a href="/noticias" class="news-link">Leer noticia</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if (empty($noticias)): ?>
            <p class="text-center text-muted-foreground text-sm" style="margin-top: 2rem;">No hay noticias publicadas aún.</p>
        <?php endif; ?>
    </div>
</section>
