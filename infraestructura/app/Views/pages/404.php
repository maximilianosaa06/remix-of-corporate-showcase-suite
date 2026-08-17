<?php
$pageTitle = '404 — No encontrado';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-404">
    <div class="text-center">
        <h1 class="error-code">404</h1>
        <h2>Página no encontrada</h2>
        <p>La página que buscas no existe o fue movida.</p>
        <a href="/" class="btn btn-primary">Volver al inicio</a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
