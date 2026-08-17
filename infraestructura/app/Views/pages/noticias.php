<!-- Noticias page -->
<section class="section-default">
    <div class="container">
        <h1 class="section-title">Noticias</h1>

        <div style="margin-bottom: 1.5rem;">
            <input type="text" id="search-noticias" class="form-input" placeholder="Buscar noticias..."
                   style="max-width: 400px;" oninput="filtrarNoticias()">
        </div>

        <?php if (empty($noticias)): ?>
            <p class="text-center text-muted-foreground text-sm">No hay noticias publicadas aún.</p>
        <?php else: ?>
            <div class="grid grid-gap-6 grid-news" id="lista-noticias">
                <?php foreach ($noticias as $n): ?>
                    <article class="card noticia-item"
                             data-title="<?= htmlspecialchars(strtolower($n['title'])) ?>"
                             data-subtitle="<?= htmlspecialchars(strtolower($n['subtitle'] ?? '')) ?>">
                        <img src="<?= htmlspecialchars(mediaUrl($n['image'] ?? null, 'noticia')) ?>"
                             alt="<?= htmlspecialchars($n['title']) ?>"
                             class="card-img card-img-wide" loading="lazy" width="800" height="560">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($n['title']) ?></h3>
                            <p class="text-xs text-muted-foreground" style="margin-top: 0.25rem;">
                                por: <?= htmlspecialchars($n['author_name'] ?? 'redactor') ?>
                            </p>
                            <p><?= htmlspecialchars($n['subtitle'] ?? mb_strimwidth($n['content'] ?? '', 0, 120, '...')) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function filtrarNoticias() {
    var q = document.getElementById('search-noticias').value.toLowerCase();
    var items = document.querySelectorAll('.noticia-item');
    items.forEach(function(el) {
        var t = el.getAttribute('data-title') + ' ' + el.getAttribute('data-subtitle');
        el.style.display = t.indexOf(q) !== -1 ? '' : 'none';
    });
}
</script>
