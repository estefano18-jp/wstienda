CREATE DATABASE TiendaRopa;

USE TiendaRopa;

CREATE TABLE Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    genero CHAR(1) NOT NULL,  -- F para Femenino, M para Masculino
    talla VARCHAR(10) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);

INSERT INTO Productos (tipo, genero, talla, precio) VALUES
('pantalon', 'F', '28', 75.00),
('camisa', 'M', 'L', 120.00);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,    
    usuario VARCHAR(50) NOT NULL, 
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL  
);

INSERT INTO usuarios (usuario, correo, contrasena)
VALUES ('edu', 'edu@gmail.com', '060622');


SELECT * FROM usuarios;
