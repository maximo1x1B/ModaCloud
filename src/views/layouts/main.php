<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'ModaCloud' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- --------------------------------------------------------
     Navbar
-------------------------------------------------------- -->
<nav class="navbar">
    <a href="/" class="navbar-brand">👗 ModaCloud</a>

    <ul class="navbar-links">
        <?php if (isLoggedIn()): ?>
            <li><a href="/productos">Productos</a></li>
            <li><a href="/proveedores">Proveedores</a></li>
            <li><a href="/inventario">Inventario</a></li>
            <li><a href="/inventario/alertas">⚠️ Alertas</a></li>
            <li class="navbar-user">
                👤 <?= htmlspecialchars($_SESSION['nombre']) ?>
                &nbsp;|&nbsp;
                <a href="/logout">Salir</a>
            </li>
        <?php else: ?>
            <li><a href="/login">Iniciar sesión</a></li>
            <li><a href="/register">Registrarse</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- --------------------------------------------------------
     Mensajes flash (éxito / error)
-------------------------------------------------------- -->
<?php if (isset($_SESSION['flash'])): ?>
    <div class="flash flash-<?= $_SESSION['flash']['tipo'] ?>">
        <?= htmlspecialchars($_SESSION['flash']['mensaje']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- --------------------------------------------------------
     Contenido de cada vista
-------------------------------------------------------- -->
<main class="container">
    <?= $content ?? '' ?>
</main>

<footer class="footer">
    <p>ModaCloud &copy; <?= date('Y') ?> — Gestión de Servicios de TI</p>
</footer>

</body>
</html>