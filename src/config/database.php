<?php

class Database {
    private $host;
    private $user;
    private $password;
    private $dbname;
    private $conn;

    public function __construct() {
        $this->host     = getenv('MYSQL_HOST')     ?: 'mysql';
        $this->user     = getenv('MYSQL_USER')     ?: 'root';
        $this->password = getenv('MYSQL_PASSWORD') ?: 'modacloud123';
        $this->dbname   = getenv('MYSQL_DATABASE') ?: 'modacloud_db';
    }

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}