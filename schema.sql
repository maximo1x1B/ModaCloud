CREATE DATABASE IF NOT EXISTS modacloud_db;
USE modacloud_db;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- --------------------------------------------------------
-- Tabla: users
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
-- Tabla: products
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
-- Tabla: suppliers
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
-- Tabla: inventory
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
-- Tabla pivote: supplier_products
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS supplier_products (
    supplier_id  INT NOT NULL,
    product_id   INT NOT NULL,
    PRIMARY KEY (supplier_id, product_id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id)  REFERENCES products(id)  ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Usuarios
-- --------------------------------------------------------
INSERT INTO users (nombre, email, password, rol) VALUES
('Admin ModaCloud', 'admin@modacloud.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Juan Cliente',    'juan@mail.com',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente'),
('Maria Dueña',     'maria@modacloud.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dueno'),
('Carlos Proveedor','carlos@textiles.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'proveedor');

-- --------------------------------------------------------
-- Productos con imágenes
-- --------------------------------------------------------
INSERT INTO products (nombre, descripcion, precio, categoria, imagen_url) VALUES
('Playera Básica Blanca',   'Playera de algodón 100%, corte recto, ideal para el día a día',                  199.00, 'Ropa',       'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400'),
('Jeans Slim Fit Azul',     'Pantalón de mezclilla stretch con corte slim, cómodo y moderno',                 599.00, 'Ropa',       'https://images.unsplash.com/photo-1542272454315-4c01d7abdf4a?w=400'),
('Vestido Floral Verano',   'Vestido ligero con estampado floral, perfecto para temporada de calor',          749.00, 'Ropa',       'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=400'),
('Sudadera Hoodie Gris',    'Sudadera con capucha de algodón grueso, bolsillo canguro',                       449.00, 'Ropa',       'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400'),
('Chamarra de Mezclilla',   'Chamarra clásica de mezclilla, corte relajado, tallas S a XL',                  899.00, 'Ropa',       'https://images.unsplash.com/photo-1543076447-215ad9ba6923?w=400'),
('Falda Midi Negra',        'Falda a la rodilla de tela fluida, cintura elástica',                            349.00, 'Ropa',       'https://images.unsplash.com/photo-1583496661160-fb5218afa9a8?w=400'),
('Bolsa de Mano Café',      'Bolsa de piel sintética con asa corta, cierre magnético, varios compartimentos', 899.00, 'Accesorios', 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400'),
('Mochila Urbana Negra',    'Mochila resistente al agua con compartimento para laptop de 15 pulgadas',        1199.00,'Accesorios', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400'),
('Gorra ModaCloud',         'Gorra bordada edición especial ModaCloud, ajustable, 100% algodón',              299.00, 'Accesorios', 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=400'),
('Cinturón de Piel Negro',  'Cinturón de piel genuina con hebilla plateada, tallas 32 a 40',                  259.00, 'Accesorios', 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=400'),
('Tenis Blancos Casual',    'Tenis de lona con suela de goma, ligeros y transpirables',                       799.00, 'Calzado',    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400'),
('Botines Negros Tacón',    'Botines de piel sintética con tacón de 5 cm, cierre lateral',                   1099.00,'Calzado',    'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=400');

-- --------------------------------------------------------
-- Proveedores
-- --------------------------------------------------------
INSERT INTO suppliers (nombre, rfc, telefono, email) VALUES
('Textiles Jalisco SA',     'TEJA800101XX1', '3312345678', 'contacto@textilesjalisco.com'),
('Confecciones MX',         'COMX900215YY2', '3398765432', 'ventas@confeccionesmx.com'),
('Piel y Moda Guadalajara', 'PMGA950312ZZ3', '3356781234', 'pedidos@pielmoda.com'),
('Calzado del Bajío SA',    'CBSA880720WW4', '3387654321', 'ventas@calzadobajio.com');

-- --------------------------------------------------------
-- Inventario inicial
-- --------------------------------------------------------
INSERT INTO inventory (product_id, cantidad, stock_minimo, tipo_movimiento) VALUES
(1,  80, 10, 'entrada'),
(2,  45, 8,  'entrada'),
(3,  30, 5,  'entrada'),
(4,  60, 10, 'entrada'),
(5,  25, 5,  'entrada'),
(6,  40, 8,  'entrada'),
(7,  20, 3,  'entrada'),
(8,  15, 3,  'entrada'),
(9,  50, 10, 'entrada'),
(10, 35, 5,  'entrada'),
(11, 4,  8,  'entrada'),
(12, 3,  5,  'entrada');

-- --------------------------------------------------------
-- Relación proveedores - productos
-- --------------------------------------------------------
INSERT INTO supplier_products (supplier_id, product_id) VALUES
(1, 1), (1, 2), (1, 4), (1, 5), (1, 6),
(2, 3), (2, 4), (2, 6),
(3, 7), (3, 8), (3, 9), (3, 10),
(4, 11),(4, 12);