<?php

class InventoryController {

    private $db;
    private $inventory;
    private $product;

    public function __construct() {
        if (!isLoggedIn()) redirect('/login');
        $database        = new Database();
        $this->db        = $database->connect();
        $this->inventory = new Inventory($this->db);
        $this->product   = new Product($this->db);
    }

    // --------------------------------------------------------
    // GET /inventario
    // --------------------------------------------------------
    public function index() {
        $inventario = $this->inventory->getAll();
        $this->render('inventario/index', 'Inventario', compact('inventario'));
    }

    // --------------------------------------------------------
    // GET /inventario/alertas
    // --------------------------------------------------------
    public function alertas() {
        $alertas = $this->inventory->getLowStock();
        $this->render('inventario/index', 'Alertas de Stock', compact('alertas'));
    }

    // --------------------------------------------------------
    // GET/POST /inventario/registrar
    // --------------------------------------------------------
    public function create() {
        $productos = $this->product->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            if ($this->hasEmptyFields($data, ['product_id', 'cantidad', 'tipo_movimiento'])) {
                $this->flash('error', 'Producto, cantidad y tipo son obligatorios');
                redirect('/inventario/registrar');
            }

            $ok = $this->inventory->create($data);

            if ($ok) {
                $this->flash('exito', 'Movimiento registrado correctamente');
                redirect('/inventario');
            } else {
                $this->flash('error', 'Error al registrar el movimiento');
                redirect('/inventario/registrar');
            }
        }

        $this->render('inventario/form', 'Registrar Movimiento', compact('productos'));
    }

    // --------------------------------------------------------
    // GET/POST /inventario/editar?id=X
    // --------------------------------------------------------
    public function edit() {
        $id         = (int)($_GET['id'] ?? 0);
        $movimiento = $this->inventory->getById($id);
        $productos  = $this->product->getAll();

        if (!$movimiento) {
            $this->flash('error', 'Movimiento no encontrado');
            redirect('/inventario');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            if ($this->hasEmptyFields($data, ['product_id', 'cantidad', 'tipo_movimiento'])) {
                $this->flash('error', 'Producto, cantidad y tipo son obligatorios');
                redirect('/inventario/editar?id=' . $id);
            }

            $ok = $this->inventory->update($id, $data);

            if ($ok) {
                $this->flash('exito', 'Movimiento actualizado correctamente');
                redirect('/inventario');
            } else {
                $this->flash('error', 'Error al actualizar el movimiento');
                redirect('/inventario/editar?id=' . $id);
            }
        }

        $this->render('inventario/form', 'Editar Movimiento', compact('movimiento', 'productos'));
    }

    // --------------------------------------------------------
    // GET /inventario/eliminar?id=X
    // --------------------------------------------------------
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);

        $ok = $this->inventory->delete($id);

        if ($ok) {
            $this->flash('exito', 'Movimiento eliminado correctamente');
        } else {
            $this->flash('error', 'Error al eliminar el movimiento');
        }

        redirect('/inventario');
    }

    // --------------------------------------------------------
    // Helpers privados
    // --------------------------------------------------------
    private function getFormData() {
        return [
            'product_id'      => (int)($_POST['product_id']      ?? 0),
            'cantidad'        => (int)($_POST['cantidad']         ?? 0),
            'stock_minimo'    => (int)($_POST['stock_minimo']     ?? 5),
            'tipo_movimiento' => trim($_POST['tipo_movimiento']   ?? 'entrada'),
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