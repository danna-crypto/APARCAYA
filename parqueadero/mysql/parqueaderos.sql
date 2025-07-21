-- Usuarios (clientes y operadores)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),  -- Guardar con password_hash()
    rol ENUM('cliente', 'operador', 'admin')
);

-- Parqueaderos
CREATE TABLE parqueaderos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    direccion VARCHAR(255),
    capacidad INT,
    latitud DECIMAL(10, 8),  -- Para mapas
    longitud DECIMAL(11, 8)   -- Para mapas
);

-- Reservas (pendientes/aceptadas/rechazadas)
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    parqueadero_id INT,
    fecha_entrada DATETIME,
    fecha_salida DATETIME,
    estado ENUM('pendiente', 'aceptada', 'rechazada'),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (parqueadero_id) REFERENCES parqueaderos(id)
);

-- Facturas
CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT,
    total DECIMAL(10, 2),
    fecha_emision DATETIME,
    FOREIGN KEY (reserva_id) REFERENCES reservas(id)
);

ALTER TABLE usuarios 
MODIFY COLUMN rol ENUM('cliente', 'operador', 'admin') NOT NULL DEFAULT 'cliente';

INSERT INTO usuarios (nombre, email, password, rol) 
VALUES (
    'Administrador', 
    'admin@parkeasy.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  # Contraseña: "password"
    'admin'
);