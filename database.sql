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
    urlPhoto VARCHAR(255) NULL
);

CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_username ON users(username);

-- Crear tabla de posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    content TEXT NOT NULL,
    imageUrl VARCHAR(255) NULL,
    totalLikes INT DEFAULT 0,
    
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_posts_userId ON posts(userId);

-- Crear tabla de likes
CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    postId INT NOT NULL,
    userId INT NOT NULL,
    
    FOREIGN KEY (postId) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_likes_postId ON likes(postId);
CREATE INDEX idx_likes_userId ON likes(userId);
