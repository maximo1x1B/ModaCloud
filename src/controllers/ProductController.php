<?php

class ProductController {

    private $db;
    private $product;

    public function __construct() {
        if (!isLoggedIn()) redirect('/login');
        $database       = new Database();
        $this->db       = $database->connect();
        $this->product  = new Product($this->db);
    }

    // --------------------------------------------------------
    // GET /productos
    // --------------------------------------------------------
    public function index() {
        $productos = $this->product->getAll();
        $this->render('productos/index', 'Productos', compact('productos'));
    }

    // --------------------------------------------------------
    // GET/POST /productos/crear
    // --------------------------------------------------------
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            if ($this->hasEmptyFields($data, ['nombre', 'precio'])) {
                $this->flash('error', 'Nombre y precio son obligatorios');
                redirect('/productos/crear');
            }

            $ok = $this->product->create($data);

            if ($ok) {
                $this->flash('exito', 'Producto creado correctamente');
                redirect('/productos');
            } else {
                $this->flash('error', 'Error al crear el producto');
                redirect('/productos/crear');
            }
        }

        $this->render('productos/form', 'Nuevo Producto');
    }

    // --------------------------------------------------------
    // GET/POST /productos/editar?id=X
    // --------------------------------------------------------
    public function edit() {
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

            if ($ok) {
                $this->flash('exito', 'Producto actualizado correctamente');
                redirect('/productos');
            } else {
                $this->flash('error', 'Error al actualizar el producto');
                redirect('/productos/editar?id=' . $id);
            }
        }

        $this->render('productos/form', 'Editar Producto', compact('producto'));
    }

    // --------------------------------------------------------
    // POST /productos/eliminar?id=X
    // --------------------------------------------------------
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);

        $ok = $this->product->delete($id);

        if ($ok) {
            $this->flash('exito', 'Producto eliminado correctamente');
        } else {
            $this->flash('error', 'Error al eliminar el producto');
        }

        redirect('/productos');
    }

    // --------------------------------------------------------
    // Helpers privados
    // --------------------------------------------------------
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