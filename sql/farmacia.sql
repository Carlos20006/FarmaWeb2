-- Crear base de datos
CREATE DATABASE IF NOT EXISTS farmacia_db;
USE farmacia_db;

-- Tabla de usuarios (autenticación)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'vendedor') DEFAULT 'vendedor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de medicamentos
CREATE TABLE medicamentos (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_generico VARCHAR(100) NOT NULL,
    presentacion VARCHAR(50),
    categoria VARCHAR(50),
    precio DECIMAL(10,2) NOT NULL,
    stock_minimo INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de lotes
CREATE TABLE lotes (
    id_lote INT AUTO_INCREMENT PRIMARY KEY,
    numero_lote VARCHAR(50) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    cantidad_inicial INT NOT NULL,
    cantidad_actual INT NOT NULL,
    id_medicamento INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento) ON DELETE CASCADE
);

-- Tabla de proveedores
CREATE TABLE proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla intermedia medicamento_proveedor
CREATE TABLE medicamento_proveedor (
    id_med_prov INT AUTO_INCREMENT PRIMARY KEY,
    id_medicamento INT NOT NULL,
    id_proveedor INT NOT NULL,
    FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento) ON DELETE CASCADE,
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor) ON DELETE CASCADE
);

-- Tabla de clientes
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    documento VARCHAR(20) UNIQUE,
    telefono VARCHAR(20),
    direccion VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de ventas
CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    id_cliente INT NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabla detalle de venta
CREATE TABLE venta_detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_lote INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id_venta) ON DELETE CASCADE,
    FOREIGN KEY (id_lote) REFERENCES lotes(id_lote)
);

-- Insertar usuario admin (contraseña: admin123)
INSERT INTO usuarios (nombre_usuario, password_hash, rol) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Datos de prueba
INSERT INTO medicamentos (nombre_generico, presentacion, categoria, precio, stock_minimo) VALUES
('Acetaminofén', 'Tableta 500mg', 'Analgésico', 15.50, 20),
('Ibuprofeno', 'Tableta 400mg', 'Antiinflamatorio', 25.00, 15),
('Amoxicilina', 'Cápsula 500mg', 'Antibiótico', 35.00, 10);

INSERT INTO lotes (numero_lote, fecha_vencimiento, cantidad_inicial, cantidad_actual, id_medicamento) VALUES
('LOT001', '2025-12-31', 100, 100, 1),
('LOT002', '2025-10-15', 50, 50, 2),
('LOT003', '2024-06-30', 30, 30, 3);

INSERT INTO proveedores (nombre, contacto, telefono, email) VALUES
('Distribuidora Salud S.A.', 'Carlos Pérez', '3001234567', 'ventas@salud.com'),
('Medicamentos del Caribe', 'Ana Gómez', '3007654321', 'contacto@medicribe.com');

INSERT INTO clientes (nombre, documento, telefono, direccion) VALUES
('Juan Martínez', '12345678', '3112223344', 'Calle 10 #20-30'),
('María Rodríguez', '87654321', '3115556677', 'Carrera 5 #15-25');
