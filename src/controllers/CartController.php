<?php

class CartController {

    private $db;
    private $cart;
    private $product;
    private $inventory;
    private $userId;

    public function __construct() {
        if (!isLoggedIn()) redirect('/login');
        $database        = new Database();
        $this->db        = $database->connect();
        $this->cart      = new Cart($this->db);
        $this->product   = new Product($this->db);
        $this->inventory = new Inventory($this->db);
        $this->userId    = (int)$_SESSION['user_id'];
    }

    // GET /carrito
    public function index() {
        $items   = $this->cart->getByUser($this->userId);
        $carrito = $this->buildCarrito($items);
        $this->render('carrito/index', 'Mi Carrito', $carrito);
    }

    // POST /carrito/agregar?id=X
    public function agregar() {
        if (!hasRole('cliente')) redirect('/productos');

        $id       = (int)($_GET['id']       ?? 0);
        $cantidad = (int)($_POST['cantidad'] ?? 1);
        $producto = $this->product->getById($id);

        if (!$producto) {
            $this->flash('error', 'Producto no encontrado');
            redirect('/productos');
        }

        $stockActual = $this->getStock($id);

        if ($stockActual <= 0) {
            $this->flash('error', $producto['nombre'] . ' está agotado');
            redirect('/productos');
        }

        // Verificar cuánto ya tiene en carrito
        $items     = $this->cart->getByUser($this->userId);
        $enCarrito = 0;
        foreach ($items as $item) {
            if ($item['product_id'] == $id) {
                $enCarrito = $item['cantidad'];
                break;
            }
        }

        if (($enCarrito + $cantidad) > $stockActual) {
            $this->flash('error', 'Solo hay ' . $stockActual . ' unidades disponibles');
            redirect('/productos');
        }

        $this->cart->addOrUpdate($this->userId, $id, $cantidad);
        $this->flash('exito', $producto['nombre'] . ' agregado al carrito');
        redirect('/productos');
    }

    // GET /carrito/actualizar?id=X&accion=sumar|restar
    public function actualizar() {
        $id     = (int)($_GET['id']     ?? 0);
        $accion = $_GET['accion'] ?? 'sumar';
        $origen = $_GET['origen'] ?? 'carrito';

        $items     = $this->cart->getByUser($this->userId);
        $cantActual = 0;
        foreach ($items as $item) {
            if ($item['product_id'] == $id) {
                $cantActual = $item['cantidad'];
                break;
            }
        }

        if ($accion === 'sumar') {
            $stockActual = $this->getStock($id);
            if ($cantActual >= $stockActual) {
                $this->flash('error', 'No hay más stock disponible');
                redirect('/carrito');
            }
            $this->cart->updateCantidad($this->userId, $id, $cantActual + 1);
        } else {
            $this->cart->updateCantidad($this->userId, $id, $cantActual - 1);
        }

        $origen === 'catalogo' ? redirect('/productos') : redirect('/carrito');

        redirect('/carrito');
    }

    // GET /carrito/quitar?id=X
    public function quitar() {
        $id = (int)($_GET['id'] ?? 0);
        $this->cart->remove($this->userId, $id);
        $this->flash('exito', 'Producto eliminado del carrito');
        redirect('/carrito');
    }

    // GET /carrito/vaciar
    public function vaciar() {
        $this->cart->clear($this->userId);
        $this->flash('exito', 'Carrito vaciado');
        redirect('/carrito');
    }

    // POST /carrito/confirmar
    public function confirmar() {
        if (!hasRole('cliente')) redirect('/productos');

        $items = $this->cart->getByUser($this->userId);

        if (empty($items)) {
            $this->flash('error', 'Tu carrito está vacío');
            redirect('/carrito');
        }

        // Verificar stock de todos primero
        foreach ($items as $item) {
            $stockActual = $this->getStock($item['product_id']);
            if ($stockActual < $item['cantidad']) {
                $this->flash('error', 'Stock insuficiente para ' . $item['nombre']);
                redirect('/carrito');
            }
        }

        // Descontar stock
        foreach ($items as $item) {
            $this->inventory->create([
                'product_id'      => $item['product_id'],
                'cantidad'        => $item['cantidad'],
                'stock_minimo'    => 5,
                'tipo_movimiento' => 'salida',
            ]);
        }

        $this->cart->clear($this->userId);
        $this->flash('exito', '¡Pedido confirmado! Gracias por tu compra 🎉');
        redirect('/productos');
    }

    // --------------------------------------------------------
    // Helpers privados
    // --------------------------------------------------------
    private function getStock($product_id) {
        $stock = $this->inventory->getStockByProduct($product_id);
        return (int)($stock['stock_actual'] ?? 0);
    }

    private function buildCarrito($items) {
        $productos = [];
        $total     = 0;
        foreach ($items as $item) {
            $stock                    = $this->getStock($item['product_id']);
            $item['subtotal']         = $item['precio'] * $item['cantidad'];
            $item['stock_disponible'] = $stock;
            $item['agotado']          = $stock <= 0;
            $total                   += $item['subtotal'];
            $productos[]              = $item;
        }
        return compact('productos', 'total');
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