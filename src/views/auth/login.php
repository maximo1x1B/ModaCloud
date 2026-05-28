<div class="auth-wrapper">
    <div class="auth-card">

        <div class="text-center mb-lg">
            <h1 class="auth-title">👗 ModaCloud</h1>
            <p class="text-muted text-small">Inicia sesión para continuar</p>
        </div>

        <form action="/login" method="POST">

            <div class="form-group">
                <label class="form-label" for="email">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="correo@ejemplo.com"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-full mt-md">
                Iniciar sesión
            </button>

        </form>

        <p class="text-center text-small text-muted mt-lg">
            ¿No tienes cuenta?
            <a href="/register">Regístrate aquí</a>
        </p>

    </div>
</div>