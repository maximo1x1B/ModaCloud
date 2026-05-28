<?php

class AdminController {

    private $db;
    private $user;

    public function __construct() {
        if (!isLoggedIn())    redirect('/login');
        if (!hasRole('admin')) redirect('/productos');
        $database    = new Database();
        $this->db    = $database->connect();
        $this->user  = new User($this->db);
    }

    // --------------------------------------------------------
    // GET /admin/usuarios
    // --------------------------------------------------------
    public function usuarios() {
        $usuarios = $this->user->getAll();
        $this->render('admin/usuarios', 'Gestión de Usuarios', compact('usuarios'));
    }

    // --------------------------------------------------------
    // GET/POST /admin/usuarios/editar?id=X
    // --------------------------------------------------------
    public function editarUsuario() {
        $id      = (int)($_GET['id'] ?? 0);
        $usuario = $this->user->getById($id);

        if (!$usuario) {
            $this->flash('error', 'Usuario no encontrado');
            redirect('/admin/usuarios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rol    = trim($_POST['rol']    ?? 'cliente');
            $nombre = trim($_POST['nombre'] ?? '');
            $email  = trim($_POST['email']  ?? '');

            $ok = $this->user->update($id, [
                'nombre' => $nombre,
                'email'  => $email,
                'rol'    => $rol,
            ]);

            $ok ? $this->flash('exito', 'Usuario actualizado correctamente')
                : $this->flash('error', 'Error al actualizar el usuario');
            redirect('/admin/usuarios');
        }

        $this->render('admin/form-usuario', 'Editar Usuario', compact('usuario'));
    }

    // --------------------------------------------------------
    // GET /admin/usuarios/eliminar?id=X
    // --------------------------------------------------------
    public function eliminarUsuario() {
        $id = (int)($_GET['id'] ?? 0);

        if ($id === (int)$_SESSION['user_id']) {
            $this->flash('error', 'No puedes eliminar tu propia cuenta');
            redirect('/admin/usuarios');
        }

        $ok = $this->user->delete($id);
        $ok ? $this->flash('exito', 'Usuario eliminado correctamente')
            : $this->flash('error', 'Error al eliminar el usuario');
        redirect('/admin/usuarios');
    }

    // --------------------------------------------------------
    // Helpers privados
    // --------------------------------------------------------
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