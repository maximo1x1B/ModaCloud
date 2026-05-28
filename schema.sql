CREATE DATABASE IF NOT EXISTS modacloud_db;
USE modacloud_db;

-- --------------------------------------------------------
-- Tabla: users (Módulo Auth - Persona A)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    rol         ENUM('cliente','dueno','proveedor','admin') NOT NULL DEFAULT 'cliente',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Tabla: products (Módulo Productos - Persona A)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(150) NOT NULL,
    descripcion  TEXT,
    precio       DECIMAL(10,2) NOT NULL,
    categoria    VARCHAR(100),
    imagen_url   VARCHAR(255),
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Tabla: suppliers (Módulo Proveedores - Persona B)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS suppliers (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(150) NOT NULL,
    rfc         VARCHAR(20),
    telefono    VARCHAR(20),
    email       VARCHAR(150),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Tabla: inventory (Módulo Inventario - Persona B)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS inventory (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    product_id       INT NOT NULL,
    cantidad         INT NOT NULL DEFAULT 0,
    stock_minimo     INT NOT NULL DEFAULT 5,
    tipo_movimiento  ENUM('entrada','salida','ajuste') NOT NULL DEFAULT 'entrada',
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Datos de prueba
-- --------------------------------------------------------
INSERT INTO users (nombre, email, password, rol) VALUES
('Admin ModaCloud', 'admin@modacloud.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Juan Cliente',   'juan@mail.com',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente');

INSERT INTO products (nombre, descripcion, precio, categoria) VALUES
('Playera Básica',   'Playera de algodón 100%',     199.00, 'Ropa'),
('Jeans Slim Fit',   'Pantalón de mezclilla stretch', 599.00, 'Ropa'),
('Bolsa de Mano',    'Bolsa de piel sintética',      899.00, 'Accesorios'),
('Gorra ModaCloud',  'Gorra bordada edición especial', 299.00, 'Accesorios');

INSERT INTO suppliers (nombre, rfc, telefono, email) VALUES
('Textiles Jalisco SA', 'TEJA800101XX1', '3312345678', 'contacto@textilesjalisco.com'),
('Confecciones MX',     'COMX900215YY2', '3398765432', 'ventas@confeccionesmx.com');

INSERT INTO inventory (product_id, cantidad, stock_minimo, tipo_movimiento) VALUES
(1, 50, 10, 'entrada'),
(2, 30, 5,  'entrada'),
(3, 15, 3,  'entrada'),
(4, 8,  5,  'entrada');