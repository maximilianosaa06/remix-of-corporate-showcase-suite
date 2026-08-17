<?php
use App\Core\Auth;

$currentPage = $_SERVER['REQUEST_URI'];
$user = Auth::user();

$navLinks = [
    '/'             => 'Sobre nosotros',
    '/proyectos'    => 'Proyectos',
    '/staff'        => 'Staff',
    '/noticias'     => 'Noticias',
    '/contacto'     => 'Contáctenos',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'TECH HUB ULS' ?></title>
    <meta name="description" content="<?= $pageDescription ?? 'Tech Hub ULS — Website corporativa' ?>">
    <meta name="author" content="TECH HUB ULS">
    <meta property="og:type" content="website">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="icon" href="/favicon.png" type="image/png">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <button type="button" class="hamburger" aria-label="Abrir menú" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        </button>

        <a href="/" class="header-logo" aria-label="TECH HUB ULS — inicio">
            <img src="/assets/images/logo-sfl-color.svg" alt="TECH HUB ULS" width="120" height="40">
        </a>

        <div class="header-right">
            <nav class="header-nav">
                <ul>
                    <?php foreach ($navLinks as $href => $label): ?>
                        <li>
                            <a href="<?= $href ?>" class="<?= $currentPage === $href ? 'active' : '' ?>">
                                <?= $label ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <div class="header-auth">
                <span class="lang-badge">ES</span>
                <?php if ($user): ?>
                    <a href="/admin">Panel de administración</a>
                <?php else: ?>
                    <a href="/login">Iniciar sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <nav class="mobile-nav" aria-label="Menú móvil">
        <ul>
            <?php foreach ($navLinks as $href => $label): ?>
                <li>
                    <a href="<?= $href ?>" class="<?= $currentPage === $href ? 'active' : '' ?>">
                        <?= $label ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li>
                <?php if ($user): ?>
                    <a href="/admin">Panel de administración</a>
                <?php else: ?>
                    <a href="/login">Iniciar sesión</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</header>

<main class="flex-1">

<?php if ($user && $user['must_change_password']): ?>
    <div style="background:#fff3e0;border-bottom:2px solid #ff9800;padding:0.625rem 1rem;text-align:center;font-size:0.875rem;">
        <strong>Debe cambiar su contraseña.</strong>
        <a href="/cambiar-password" style="margin-left:0.5rem;color:var(--primary);text-decoration:underline;">Cambiar ahora</a>
    </div>
<?php endif; ?>
