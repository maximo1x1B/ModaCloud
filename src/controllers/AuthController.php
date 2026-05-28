<?php

class AuthController {

    private $db;
    private $user;

    public function __construct() {
        $database   = new Database();
        $this->db   = $database->connect();
        $this->user = new User($this->db);
    }

    // --------------------------------------------------------
    // GET/POST /login
    // --------------------------------------------------------
    public function login() {
        if (isLoggedIn()) redirect('/productos');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $this->flash('error', 'Completa todos los campos');
                redirect('/login');
            }

            $user = $this->user->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $this->flash('error', 'Credenciales incorrectas');
                redirect('/login');
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre']  = $user['nombre'];
            $_SESSION['rol']     = $user['rol'];

            $this->flash('exito', 'Bienvenido, ' . $user['nombre']);
            redirect('/productos');
        }

        $this->render('auth/login', 'Iniciar sesión');
    }

    // --------------------------------------------------------
    // GET/POST /register
    // --------------------------------------------------------
    public function register() {
        if (isLoggedIn()) redirect('/productos');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre   = trim($_POST['nombre']   ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');
            $rol = 'cliente';

            if (empty($nombre) || empty($email) || empty($password)) {
                $this->flash('error', 'Completa todos los campos');
                redirect('/register');
            }

            if ($this->user->findByEmail($email)) {
                $this->flash('error', 'El email ya está registrado');
                redirect('/register');
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ok   = $this->user->create($nombre, $email, $hash, $rol);

            if ($ok) {
                $this->flash('exito', 'Cuenta creada, inicia sesión');
                redirect('/login');
            } else {
                $this->flash('error', 'Error al crear la cuenta');
                redirect('/register');
            }
        }

        $this->render('auth/register', 'Registrarse');
    }

    // --------------------------------------------------------
    // GET /logout
    // --------------------------------------------------------
    public function logout() {
        session_destroy();
        redirect('/login');
    }

    // --------------------------------------------------------
    // Helpers privados
    // --------------------------------------------------------
    private function render($vista, $titulo = 'ModaCloud') {
        $pageTitle = $titulo;
        ob_start();
        require_once BASE_PATH . "views/{$vista}.php";
        $content = ob_get_clean();
        require_once BASE_PATH . 'views/layouts/main.php';
    }

    private function flash($tipo, $mensaje) {
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    }
}