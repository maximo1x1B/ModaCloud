<?php

class Supplier {

    private $db;
    private $table = 'suppliers';

    public function __construct($db) {
        $this->db = $db;
    }

    // --------------------------------------------------------
    // Obtener todos los proveedores
    // --------------------------------------------------------
    public function getAll() {
        $sql  = "SELECT * FROM {$this->table} 
                 ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --------------------------------------------------------
    // Obtener proveedor por ID
    // --------------------------------------------------------
    public function getById($id) {
        $sql  = "SELECT * FROM {$this->table} 
                 WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // --------------------------------------------------------
    // Obtener productos de un proveedor
    // --------------------------------------------------------
    public function getProductos($supplier_id) {
        $sql  = "SELECT p.* FROM products p
                 INNER JOIN supplier_products sp ON sp.product_id = p.id
                 WHERE sp.supplier_id = :supplier_id
                 ORDER BY p.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':supplier_id' => $supplier_id]);
        return $stmt->fetchAll();
    }

    // --------------------------------------------------------
    // Crear proveedor
    // --------------------------------------------------------
    public function create($data) {
        $sql  = "INSERT INTO {$this->table} 
                 (nombre, rfc, telefono, email) 
                 VALUES (:nombre, :rfc, :telefono, :email)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':rfc'      => $data['rfc'],
            ':telefono' => $data['telefono'],
            ':email'    => $data['email'],
        ]);
    }

    // --------------------------------------------------------
    // Actualizar proveedor
    // --------------------------------------------------------
    public function update($id, $data) {
        $sql  = "UPDATE {$this->table} 
                 SET nombre   = :nombre,
                     rfc      = :rfc,
                     telefono = :telefono,
                     email    = :email
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':rfc'      => $data['rfc'],
            ':telefono' => $data['telefono'],
            ':email'    => $data['email'],
            ':id'       => $id,
        ]);
    }

    // --------------------------------------------------------
    // Eliminar proveedor
    // --------------------------------------------------------
    public function delete($id) {
        $sql  = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
