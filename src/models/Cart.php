<?php

class Cart {

    private $db;
    private $table = 'cart';

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener carrito del usuario con info del producto
    public function getByUser($user_id) {
        $sql  = "SELECT c.*, p.nombre, p.precio, p.imagen_url, p.categoria
                 FROM {$this->table} c
                 INNER JOIN products p ON p.id = c.product_id
                 WHERE c.user_id = :user_id
                 ORDER BY c.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    // Agregar o actualizar cantidad
    public function addOrUpdate($user_id, $product_id, $cantidad) {
        $sql  = "INSERT INTO {$this->table} (user_id, product_id, cantidad)
                 VALUES (:user_id, :product_id, :cantidad)
                 ON DUPLICATE KEY UPDATE cantidad = cantidad + :cantidad2";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'    => $user_id,
            ':product_id' => $product_id,
            ':cantidad'   => $cantidad,
            ':cantidad2'  => $cantidad,
        ]);
    }

    // Actualizar cantidad exacta
    public function updateCantidad($user_id, $product_id, $cantidad) {
        if ($cantidad <= 0) return $this->remove($user_id, $product_id);
        $sql  = "UPDATE {$this->table}
                 SET cantidad = :cantidad
                 WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':cantidad'   => $cantidad,
            ':user_id'    => $user_id,
            ':product_id' => $product_id,
        ]);
    }

    // Quitar producto
    public function remove($user_id, $product_id) {
        $sql  = "DELETE FROM {$this->table}
                 WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'    => $user_id,
            ':product_id' => $product_id,
        ]);
    }

    // Vaciar carrito
    public function clear($user_id) {
        $sql  = "DELETE FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }

    // Contar items
    public function count($user_id) {
        $sql  = "SELECT SUM(cantidad) as total
                 FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $r = $stmt->fetch();
        return (int)($r['total'] ?? 0);
    }
}