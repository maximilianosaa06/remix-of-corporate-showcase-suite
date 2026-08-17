<!-- Login page -->
<div style="max-width: 400px; margin: 3rem auto; padding: 2rem;">
    <div class="hero-gradient" style="border-radius: var(--radius); padding: 2rem; color: #fff;">
        <h1 style="text-align: center; font-size: 1.5rem; font-weight: 800; color: #fff;">Iniciar sesión</h1>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(255,255,255,0.15); border-radius: var(--radius); padding: 0.75rem; margin-top: 1rem; font-size: 0.875rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" style="margin-top: 1.5rem;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::generateCsrfToken() ?>">

            <div class="form-group">
                <label class="form-label" for="email" style="color: #fff;">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-input"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus
                       style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.25); color: #fff;">
            </div>

            <div class="form-group">
                <label class="form-label" for="password" style="color: #fff;">Contraseña</label>
                <input type="password" id="password" name="password" class="form-input" required
                       style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.25); color: #fff;">
            </div>

            <button type="submit" class="btn btn-destructive w-full" style="margin-top: 0.5rem;">Entrar</button>
        </form>
    </div>
</div>
