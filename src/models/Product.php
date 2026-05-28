<?php

class Product {

    private $db;
    private $table = 'products';

    public function __construct($db) {
        $this->db = $db;
    }

    // --------------------------------------------------------
    // Obtener todos los productos
    // --------------------------------------------------------
    public function getAll() {
        $sql  = "SELECT * FROM {$this->table} 
                 ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --------------------------------------------------------
    // Obtener producto por ID
    // --------------------------------------------------------
    public function getById($id) {
        $sql  = "SELECT * FROM {$this->table} 
                 WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // --------------------------------------------------------
    // Obtener productos por categoría
    // --------------------------------------------------------
    public function getByCategoria($categoria) {
        $sql  = "SELECT * FROM {$this->table} 
                 WHERE categoria = :categoria 
                 ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':categoria' => $categoria]);
        return $stmt->fetchAll();
    }

    // --------------------------------------------------------
    // Crear producto
    // --------------------------------------------------------
    public function create($data) {
        $sql  = "INSERT INTO {$this->table} 
                 (nombre, descripcion, precio, categoria, imagen_url) 
                 VALUES (:nombre, :descripcion, :precio, :categoria, :imagen_url)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':precio'      => $data['precio'],
            ':categoria'   => $data['categoria'],
            ':imagen_url'  => $data['imagen_url'],
        ]);
    }

    // --------------------------------------------------------
    // Actualizar producto
    // --------------------------------------------------------
    public function update($id, $data) {
        $sql  = "UPDATE {$this->table} 
                 SET nombre      = :nombre,
                     descripcion = :descripcion,
                     precio      = :precio,
                     categoria   = :categoria,
                     imagen_url  = :imagen_url
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':precio'      => $data['precio'],
            ':categoria'   => $data['categoria'],
            ':imagen_url'  => $data['imagen_url'],
            ':id'          => $id,
        ]);
    }

    // --------------------------------------------------------
    // Eliminar producto
    // --------------------------------------------------------
    public function delete($id) {
        $sql  = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // --------------------------------------------------------
    // Obtener categorías únicas
    // --------------------------------------------------------
    public function getCategorias() {
        $sql  = "SELECT DISTINCT categoria 
                 FROM {$this->table} 
                 WHERE categoria IS NOT NULL 
                 ORDER BY categoria ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}