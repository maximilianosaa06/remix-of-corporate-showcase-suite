<?php
use App\Core\Database;

$db = Database::getInstance();

$footerLinks = [];
try {
    $footerLinks = $db->fetchAll(
        "SELECT * FROM enlaces_footer ORDER BY grupo, orden"
    );
} catch (\Throwable $e) {
    $footerLinks = [];
}

if (empty($footerLinks)) {
    $footerLinks = [
        ['grupo' => 'Sitio',     'etiqueta' => 'Inicio',      'url' => '/'],
        ['grupo' => 'Sitio',     'etiqueta' => 'Proyectos',   'url' => '/proyectos'],
        ['grupo' => 'Sitio',     'etiqueta' => 'Staff',       'url' => '/staff'],
        ['grupo' => 'Contenido', 'etiqueta' => 'Noticias',    'url' => '/noticias'],
        ['grupo' => 'Contenido', 'etiqueta' => 'Contacto',    'url' => '/contacto'],
        ['grupo' => 'Contenido', 'etiqueta' => 'Iniciar sesión', 'url' => '/login'],
    ];
}

$grupos = [];
foreach ($footerLinks as $enlace) {
    $grupos[$enlace['grupo']][] = $enlace;
}

$footerInfo = null;
try {
    $footerInfo = $db->fetchOne(
        "SELECT email, phone, address, copyright_text,
                social_facebook, social_linkedin, social_twitter,
                social_instagram, social_youtube
         FROM footer_info
         ORDER BY id
         LIMIT 1"
    );
} catch (\Throwable $e) {
    $footerInfo = null;
}
?>

</main>

<footer class="site-footer">
    <div class="footer-inner">
        <p class="footer-logo-text">Software Factory Lab</p>
        <img src="/assets/images/logo-sfl.svg" alt="TECH HUB ULS" width="150" height="50" class="footer-logo">

        <?php if ($footerInfo && ($footerInfo['email'] || $footerInfo['phone'] || $footerInfo['address'])): ?>
        <div class="footer-contact" style="margin-bottom:1rem;font-size:0.875rem;">
            <?php if ($footerInfo['email']): ?>
                <p style="margin:0.25rem 0;">&#9993; <?= e($footerInfo['email']) ?></p>
            <?php endif; ?>
            <?php if ($footerInfo['phone']): ?>
                <p style="margin:0.25rem 0;">&#9742; <?= e($footerInfo['phone']) ?></p>
            <?php endif; ?>
            <?php if ($footerInfo['address']): ?>
                <p style="margin:0.25rem 0;">&#127968; <?= e($footerInfo['address']) ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="footer-links">
            <?php foreach ($grupos as $grupo => $enlaces): ?>
                <div class="footer-links-group">
                    <h4><?= htmlspecialchars($grupo) ?></h4>
                    <ul>
                        <?php foreach ($enlaces as $enlace): ?>
                            <li>
                                <a href="<?= htmlspecialchars($enlace['url']) ?>">
                                    <?= htmlspecialchars($enlace['etiqueta']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="footer-social">
            <h4>Síguenos en:</h4>
            <div class="footer-social-icons">
                <?php if ($footerInfo && !empty($footerInfo['social_facebook'])): ?>
                    <a href="<?= e($footerInfo['social_facebook']) ?>" aria-label="Facebook" target="_blank" rel="noopener">&#x1F4F1;</a>
                <?php else: ?>
                    <a href="#" aria-label="Facebook">&#x1F4F1;</a>
                <?php endif; ?>
                <?php if ($footerInfo && !empty($footerInfo['social_linkedin'])): ?>
                    <a href="<?= e($footerInfo['social_linkedin']) ?>" aria-label="LinkedIn" target="_blank" rel="noopener">&#x1F4BC;</a>
                <?php else: ?>
                    <a href="#" aria-label="LinkedIn">&#x1F4BC;</a>
                <?php endif; ?>
                <?php if ($footerInfo && !empty($footerInfo['social_twitter'])): ?>
                    <a href="<?= e($footerInfo['social_twitter']) ?>" aria-label="Twitter" target="_blank" rel="noopener">&#x1F426;</a>
                <?php else: ?>
                    <a href="#" aria-label="Twitter">&#x1F426;</a>
                <?php endif; ?>
                <?php if ($footerInfo && !empty($footerInfo['social_instagram'])): ?>
                    <a href="<?= e($footerInfo['social_instagram']) ?>" aria-label="Instagram" target="_blank" rel="noopener">&#x1F4F7;</a>
                <?php else: ?>
                    <a href="#" aria-label="Instagram">&#x1F4F7;</a>
                <?php endif; ?>
                <?php if ($footerInfo && !empty($footerInfo['social_youtube'])): ?>
                    <a href="<?= e($footerInfo['social_youtube']) ?>" aria-label="YouTube" target="_blank" rel="noopener">&#x25B6;</a>
                <?php else: ?>
                    <a href="#" aria-label="YouTube">&#x25B6;</a>
                <?php endif; ?>
            </div>
        </div>

        <?php
        $copyrightText = ($footerInfo && !empty($footerInfo['copyright_text']))
            ? $footerInfo['copyright_text']
            : 'TECH HUB ULS. Todos los derechos reservados.';
        ?>
        <p class="footer-copyright">&copy; <?= date('Y') ?> <?= e($copyrightText) ?></p>
    </div>
</footer>

<script src="/js/app.js"></script>
</body>
</html>
