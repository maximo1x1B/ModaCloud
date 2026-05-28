<?php

class ProductController {

    private $db;
    private $product;

    public function __construct() {
        if (!isLoggedIn()) redirect('/login');
        $database      = new Database();
        $this->db      = $database->connect();
        $this->product = new Product($this->db);
    }

    public function index() {
        $productos = $this->product->getAll();

        $carritoMap = [];
        if (isLoggedIn() && hasRole('cliente')) {
            $database  = new Database();
            $cartModel = new Cart($database->connect());
            $items     = $cartModel->getByUser($_SESSION['user_id']);
            foreach ($items as $item) {
                $carritoMap[$item['product_id']] = $item['cantidad'];
            }
        }

        $this->render('productos/index', 'Productos', compact('productos', 'carritoMap'));
    }

    public function create() {
        if (!hasRole('admin') && !hasRole('gerente')) redirect('/productos');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            if ($this->hasEmptyFields($data, ['nombre', 'precio'])) {
                $this->flash('error', 'Nombre y precio son obligatorios');
                redirect('/productos/crear');
            }

            $ok = $this->product->create($data);
            $ok ? $this->flash('exito', 'Producto creado correctamente')
                : $this->flash('error', 'Error al crear el producto');
            redirect('/productos');
        }

        $this->render('productos/form', 'Nuevo Producto');
    }

    public function edit() {
        if (!hasRole('admin') && !hasRole('gerente')) redirect('/productos');

        $id      = (int)($_GET['id'] ?? 0);
        $producto = $this->product->getById($id);

        if (!$producto) {
            $this->flash('error', 'Producto no encontrado');
            redirect('/productos');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            if ($this->hasEmptyFields($data, ['nombre', 'precio'])) {
                $this->flash('error', 'Nombre y precio son obligatorios');
                redirect('/productos/editar?id=' . $id);
            }

            $ok = $this->product->update($id, $data);
            $ok ? $this->flash('exito', 'Producto actualizado correctamente')
                : $this->flash('error', 'Error al actualizar el producto');
            redirect('/productos');
        }

        $this->render('productos/form', 'Editar Producto', compact('producto'));
    }

    public function delete() {
        if (!hasRole('admin') && !hasRole('gerente')) redirect('/productos');

        $id = (int)($_GET['id'] ?? 0);
        $ok = $this->product->delete($id);

        $ok ? $this->flash('exito', 'Producto eliminado correctamente')
            : $this->flash('error', 'Error al eliminar el producto');
        redirect('/productos');
    }

    private function getFormData() {
        return [
            'nombre'      => trim($_POST['nombre']      ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio'      => trim($_POST['precio']      ?? ''),
            'categoria'   => trim($_POST['categoria']   ?? ''),
            'imagen_url'  => trim($_POST['imagen_url']  ?? ''),
        ];
    }

    private function hasEmptyFields($data, $required) {
        foreach ($required as $field) {
            if (empty($data[$field])) return true;
        }
        return false;
    }

    private function render($vista, $titulo = 'ModaCloud', $vars = []) {
        $pageTitle = $titulo;
        extract($vars);
        ob_start();
        require_once BASE_PATH . "views/{$vista}.php";
        $content = ob_get_clean();
        require_once BASE_PATH . 'views/layouts/main.php';
    }

    private function flash($tipo, $mensaje) {
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    }
}