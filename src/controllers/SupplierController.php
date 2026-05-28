<?php

class SupplierController {

    private $db;
    private $supplier;

    public function __construct() {
        if (!isLoggedIn()) redirect('/login');
        $database        = new Database();
        $this->db        = $database->connect();
        $this->supplier  = new Supplier($this->db);
    }

    // --------------------------------------------------------
    // GET /proveedores
    // --------------------------------------------------------
    public function index() {
        $proveedores = $this->supplier->getAll();
        $this->render('proveedores/index', 'Proveedores', compact('proveedores'));
    }

    // --------------------------------------------------------
    // GET /proveedores/detalle?id=X
    // --------------------------------------------------------
    public function show() {
        $id        = (int)($_GET['id'] ?? 0);
        $proveedor = $this->supplier->getById($id);

        if (!$proveedor) {
            $this->flash('error', 'Proveedor no encontrado');
            redirect('/proveedores');
        }

        $this->render('proveedores/show', $proveedor['nombre'], compact('proveedor'));
    }

    // --------------------------------------------------------
    // GET/POST /proveedores/crear
    // --------------------------------------------------------
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            if ($this->hasEmptyFields($data, ['nombre'])) {
                $this->flash('error', 'El nombre es obligatorio');
                redirect('/proveedores/crear');
            }

            $ok = $this->supplier->create($data);

            if ($ok) {
                $this->flash('exito', 'Proveedor creado correctamente');
                redirect('/proveedores');
            } else {
                $this->flash('error', 'Error al crear el proveedor');
                redirect('/proveedores/crear');
            }
        }

        $this->render('proveedores/form', 'Nuevo Proveedor');
    }

    // --------------------------------------------------------
    // GET/POST /proveedores/editar?id=X
    // --------------------------------------------------------
    public function edit() {
        $id        = (int)($_GET['id'] ?? 0);
        $proveedor = $this->supplier->getById($id);

        if (!$proveedor) {
            $this->flash('error', 'Proveedor no encontrado');
            redirect('/proveedores');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            if ($this->hasEmptyFields($data, ['nombre'])) {
                $this->flash('error', 'El nombre es obligatorio');
                redirect('/proveedores/editar?id=' . $id);
            }

            $ok = $this->supplier->update($id, $data);

            if ($ok) {
                $this->flash('exito', 'Proveedor actualizado correctamente');
                redirect('/proveedores');
            } else {
                $this->flash('error', 'Error al actualizar el proveedor');
                redirect('/proveedores/editar?id=' . $id);
            }
        }

        $this->render('proveedores/form', 'Editar Proveedor', compact('proveedor'));
    }

    // --------------------------------------------------------
    // POST /proveedores/eliminar?id=X
    // --------------------------------------------------------
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);

        $ok = $this->supplier->delete($id);

        if ($ok) {
            $this->flash('exito', 'Proveedor eliminado correctamente');
        } else {
            $this->flash('error', 'Error al eliminar el proveedor');
        }

        redirect('/proveedores');
    }

    // --------------------------------------------------------
    // Helpers privados
    // --------------------------------------------------------
    private function getFormData() {
        return [
            'nombre'   => trim($_POST['nombre']   ?? ''),
            'rfc'      => trim($_POST['rfc']      ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email'    => trim($_POST['email']    ?? ''),
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