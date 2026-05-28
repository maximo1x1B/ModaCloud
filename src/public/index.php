<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// --------------------------------------------------------
// Tabla de rutas: 'ruta' => [Controlador, método]
// --------------------------------------------------------
$routes = [
    '/'                   => ['AuthController',      'login'],

    '/login'              => ['AuthController',      'login'],
    '/register'           => ['AuthController',      'register'],
    '/logout'             => ['AuthController',      'logout'],

    '/productos'          => ['ProductController',   'index'],
    '/productos/crear'    => ['ProductController',   'create'],
    '/productos/editar'   => ['ProductController',   'edit'],
    '/productos/eliminar' => ['ProductController',   'delete'],

    '/proveedores'          => ['SupplierController', 'index'],
    '/proveedores/crear'    => ['SupplierController', 'create'],
    '/proveedores/editar'   => ['SupplierController', 'edit'],
    '/proveedores/eliminar' => ['SupplierController', 'delete'],
    '/proveedores/detalle'  => ['SupplierController', 'show'],

    '/inventario'             => ['InventoryController', 'index'],
    '/inventario/registrar'   => ['InventoryController', 'create'],
    '/inventario/editar'      => ['InventoryController', 'edit'],
    '/inventario/eliminar'    => ['InventoryController', 'delete'],
    '/inventario/alertas'     => ['InventoryController', 'alertas'],
];

// --------------------------------------------------------
// Resolver la ruta
// --------------------------------------------------------
function resolve($routes) {
    $request = strtok($_SERVER['REQUEST_URI'], '?');
    $request = rtrim($request, '/') ?: '/';

    if (array_key_exists($request, $routes)) {
        [$class, $method] = $routes[$request];
        $controller = new $class();
        $controller->$method();
    } else {
        http_response_code(404);
        require_once BASE_PATH . 'views/404.php';
    }
}

resolve($routes);