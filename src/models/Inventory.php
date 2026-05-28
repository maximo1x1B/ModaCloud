<?php

class Inventory {

    private $db;
    private $table = 'inventory';

    public function __construct($db) {
        $this->db = $db;
    }

    // --------------------------------------------------------
    // Obtener todo el inventario (con nombre del producto)
    // --------------------------------------------------------
    public function getAll() {
        $sql  = "SELECT i.*, p.nombre AS producto, p.categoria
                 FROM {$this->table} i
                 INNER JOIN products p ON p.id = i.product_id
                 ORDER BY i.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --------------------------------------------------------
    // Obtener movimiento por ID
    // --------------------------------------------------------
    public function getById($id) {
        $sql  = "SELECT i.*, p.nombre AS producto
                 FROM {$this->table} i
                 INNER JOIN products p ON p.id = i.product_id
                 WHERE i.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // --------------------------------------------------------
    // Obtener productos con stock bajo
    // --------------------------------------------------------
    public function getLowStock() {
        $sql  = "SELECT i.*, p.nombre AS producto, p.categoria
                 FROM {$this->table} i
                 INNER JOIN products p ON p.id = i.product_id
                 WHERE i.cantidad <= i.stock_minimo
                 ORDER BY i.cantidad ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --------------------------------------------------------
    // Obtener stock actual de un producto
    // --------------------------------------------------------
    public function getStockByProduct($product_id) {
        $sql  = "SELECT SUM(
                     CASE tipo_movimiento
                         WHEN 'entrada' THEN cantidad
                         WHEN 'salida'  THEN -cantidad
                         WHEN 'ajuste'  THEN cantidad
                     END
                 ) AS stock_actual
                 FROM {$this->table}
                 WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $product_id]);
        return $stmt->fetch();
    }

    // --------------------------------------------------------
    // Registrar movimiento
    // --------------------------------------------------------
    public function create($data) {
        $sql  = "INSERT INTO {$this->table}
                 (product_id, cantidad, stock_minimo, tipo_movimiento)
                 VALUES (:product_id, :cantidad, :stock_minimo, :tipo_movimiento)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':product_id'      => $data['product_id'],
            ':cantidad'        => $data['cantidad'],
            ':stock_minimo'    => $data['stock_minimo'],
            ':tipo_movimiento' => $data['tipo_movimiento'],
        ]);
    }

    // --------------------------------------------------------
    // Actualizar movimiento
    // --------------------------------------------------------
    public function update($id, $data) {
        $sql  = "UPDATE {$this->table}
                 SET product_id      = :product_id,
                     cantidad        = :cantidad,
                     stock_minimo    = :stock_minimo,
                     tipo_movimiento = :tipo_movimiento
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':product_id'      => $data['product_id'],
            ':cantidad'        => $data['cantidad'],
            ':stock_minimo'    => $data['stock_minimo'],
            ':tipo_movimiento' => $data['tipo_movimiento'],
            ':id'              => $id,
        ]);
    }

    // --------------------------------------------------------
    // Eliminar movimiento
    // --------------------------------------------------------
    public function delete($id) {
        $sql  = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}