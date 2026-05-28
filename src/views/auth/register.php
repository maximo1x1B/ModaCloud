<div class="auth-wrapper">
    <div class="auth-card">

        <div class="text-center mb-lg">
            <h1 class="auth-title">👗 ModaCloud</h1>
            <p class="text-muted text-small">Crea tu cuenta para comenzar</p>
        </div>

        <form action="/register" method="POST">

            <div class="form-group">
                <label class="form-label" for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                       placeholder="Tu nombre" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="correo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Mínimo 8 caracteres" minlength="8" required>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-md">
                Crear cuenta
            </button>

        </form>

        <p class="text-center text-small text-muted mt-lg">
            ¿Ya tienes cuenta? <a href="/login">Inicia sesión</a>
        </p>

    </div>
</div>