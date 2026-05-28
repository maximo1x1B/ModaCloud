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

<nav class="navbar">
    <a href="/" class="navbar-brand">👗 ModaCloud</a>

    <ul class="navbar-links">
        <?php if (isLoggedIn()): ?>

            <?php if (hasRole('cliente')): ?>
                <li><a href="/productos">Catálogo</a></li>

            <?php if (hasRole('cliente')): ?>
                <li>
                    <?php
                    $cartCount = 0;
                    if (isLoggedIn()) {
                        $cartDb    = new Database();
                        $cartConn  = $cartDb->connect();
                        $cartModel = new Cart($cartConn);
                        $cartCount = $cartModel->count($_SESSION['user_id']);
                    }
                    ?>
                    <a href="/carrito" class="btn btn-outline btn-sm" style="position:relative">
                        🛒 Carrito
                        <?php if ($cartCount > 0): ?>
                            <span style="
                                position:absolute;top:-6px;right:-6px;
                                background:var(--color-primary);color:#fff;
                                border-radius:var(--radius-full);
                                font-size:10px;width:18px;height:18px;
                                display:flex;align-items:center;justify-content:center;
                                font-weight:600">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php elseif (hasRole('admin')): ?>
                <li><a href="/productos">Productos</a></li>
                <li><a href="/proveedores">Proveedores</a></li>
                <li><a href="/inventario">Inventario</a></li>
                <li><a href="/admin/usuarios">👥 Usuarios</a></li>

            <?php elseif (hasRole('gerente')): ?>
                <li><a href="/productos">Productos</a></li>
                <li><a href="/proveedores">Proveedores</a></li>
                <li><a href="/inventario">Inventario</a></li>

            <?php endif; ?>

            <li class="navbar-user">
                👤 <?= htmlspecialchars($_SESSION['nombre']) ?>
                <span class="badge badge-primary" style="margin-left:4px">
                    <?= ucfirst($_SESSION['rol']) ?>
                </span>
                &nbsp;|&nbsp;
                <a href="/logout">Salir</a>
            </li>

        <?php else: ?>
            <li><a href="/login">Iniciar sesión</a></li>
            <li><a href="/register">Registrarse</a></li>
        <?php endif; ?>
    </ul>
</nav>

<?php if (isset($_SESSION['flash'])): ?>
    <div class="flash flash-<?= $_SESSION['flash']['tipo'] ?>">
        <?= htmlspecialchars($_SESSION['flash']['mensaje']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<main class="container">
    <?= $content ?? '' ?>
</main>

<footer class="footer">
    <p>ModaCloud &copy; <?= date('Y') ?> — Gestión de Servicios de TI</p>
</footer>

</body>
</html>