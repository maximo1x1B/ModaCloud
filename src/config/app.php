<?php

// Rutas base
define('BASE_URL', 'http://localhost');
define('BASE_PATH', __DIR__ . '/../');

// Sesiones
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload de clases (models y controllers)
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . 'models/'      . $class . '.php',
        BASE_PATH . 'controllers/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Helper: redirigir
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}

// Helper: verificar si hay sesión activa
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper: verificar rol
function hasRole($rol) {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === $rol;
}