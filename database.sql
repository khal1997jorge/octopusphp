-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS octopusphp;

-- Usar la base de datos
USE octopusphp;

-- Crear tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(10) NULL,
    photo LONGBLOB NULL
);

CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_username ON users(username);
