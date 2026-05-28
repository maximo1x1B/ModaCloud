<?php

class User {

    private $db;
    private $table = 'users';

    public function __construct($db) {
        $this->db = $db;
    }

    // --------------------------------------------------------
    // Buscar usuario por email
    // --------------------------------------------------------
    public function findByEmail($email) {
        $sql  = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // --------------------------------------------------------
    // Crear usuario
    // --------------------------------------------------------
    public function create($nombre, $email, $password, $rol = 'cliente') {
        $sql  = "INSERT INTO {$this->table} 
                 (nombre, email, password, rol) 
                 VALUES (:nombre, :email, :password, :rol)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':password' => $password,
            ':rol'      => $rol,
        ]);
    }

    // --------------------------------------------------------
    // Obtener todos los usuarios
    // --------------------------------------------------------
    public function getAll() {
        $sql  = "SELECT id, nombre, email, rol, created_at 
                 FROM {$this->table} 
                 ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --------------------------------------------------------
    // Obtener usuario por ID
    // --------------------------------------------------------
    public function getById($id) {
        $sql  = "SELECT id, nombre, email, rol, created_at 
                 FROM {$this->table} 
                 WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // --------------------------------------------------------
    // Actualizar usuario
    // --------------------------------------------------------
    public function update($id, $data) {
        $sql  = "UPDATE {$this->table} 
                 SET nombre = :nombre, email = :email, rol = :rol 
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':email'  => $data['email'],
            ':rol'    => $data['rol'],
            ':id'     => $id,
        ]);
    }

    // --------------------------------------------------------
    // Eliminar usuario
    // --------------------------------------------------------
    public function delete($id) {
        $sql  = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}