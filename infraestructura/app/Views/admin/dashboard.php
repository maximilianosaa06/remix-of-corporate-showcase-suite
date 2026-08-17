<!-- Admin Dashboard -->
<div class="section-default">
    <div class="container">
        <h1 class="section-title">Panel de administración</h1>

        <div style="margin-bottom: 1.5rem;">
            <p class="text-muted-foreground">Bienvenido, <strong><?= htmlspecialchars($user['username']) ?></strong>.
               Rol: <span class="badge"><?= htmlspecialchars($user['role_name']) ?></span></p>
        </div>

        <div class="grid grid-gap-6" style="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));">
            <?php if ($user['role_name'] === 'admin'): ?>
                <a href="/admin/proyectos" class="card" style="text-decoration:none;">
                    <div class="card-body text-center">
                        <h3>Proyectos</h3>
                        <p class="text-sm text-muted-foreground">Gestionar proyectos y servicios</p>
                    </div>
                </a>
                <a href="/admin/staff" class="card" style="text-decoration:none;">
                    <div class="card-body text-center">
                        <h3>Staff</h3>
                        <p class="text-sm text-muted-foreground">Gestionar miembros del equipo</p>
                    </div>
                </a>
            <?php endif; ?>

            <?php if (in_array($user['role_name'], ['admin', 'editor', 'redactor'])): ?>
                <a href="/admin/noticias" class="card" style="text-decoration:none;">
                    <div class="card-body text-center">
                        <h3>Noticias</h3>
                        <p class="text-sm text-muted-foreground">Crear y gestionar noticias</p>
                    </div>
                </a>
            <?php endif; ?>

            <?php if (in_array($user['role_name'], ['admin', 'editor'])): ?>
                <a href="/admin/contacto" class="card" style="text-decoration:none;">
                    <div class="card-body text-center">
                        <h3>Contacto
                            <?php if ($pendingContacts > 0): ?>
                                <span class="badge badge-warning" style="margin-left:0.5rem;font-size:0.75rem;"><?= $pendingContacts ?> pendiente<?= $pendingContacts > 1 ? 's' : '' ?></span>
                            <?php endif; ?>
                        </h3>
                        <p class="text-sm text-muted-foreground">Solicitudes de contacto recibidas</p>
                    </div>
                </a>
            <?php endif; ?>

            <?php if ($user['role_name'] === 'admin'): ?>
                <a href="/admin/contenido" class="card" style="text-decoration:none;">
                    <div class="card-body text-center">
                        <h3>Contenido</h3>
                        <p class="text-sm text-muted-foreground">Editar contenido institucional</p>
                    </div>
                </a>
                <a href="/admin/footer" class="card" style="text-decoration:none;">
                    <div class="card-body text-center">
                        <h3>Footer</h3>
                        <p class="text-sm text-muted-foreground">Gestionar enlaces del pie de página</p>
                    </div>
                </a>
                <a href="/admin/usuarios" class="card" style="text-decoration:none;">
                    <div class="card-body text-center">
                        <h3>Usuarios</h3>
                        <p class="text-sm text-muted-foreground">Gestionar cuentas y roles</p>
                    </div>
                </a>
            <?php endif; ?>
        </div>

        <div style="margin-top: 2rem;">
            <a href="/logout" class="btn btn-outline">Cerrar sesión</a>
        </div>
    </div>
</div>
