CREATE DATABASE IF NOT EXISTS web_cafe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE web_cafe;

CREATE TABLE admin (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE menu (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    deskripsi TEXT NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE galeri (
    id INT NOT NULL AUTO_INCREMENT,
    judul VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin (nama, username, password)
VALUES ('Administrator', 'admin', '$2y$10$98vbxh4xT65X0Rnc9/L33eWkPdxz8VL4hbvd338D7IILqDOqYj4e6');

INSERT INTO menu (nama, kategori, deskripsi, harga, stok, gambar) VALUES
('Cafe Latte', 'Coffee', 'Espresso dengan susu creamy yang lembut.', 25000, 20, NULL),
('Matcha Latte', 'Non-Coffee', 'Matcha creamy dengan rasa yang seimbang.', 28000, 15, NULL),
('Croissant', 'Food', 'Croissant renyah dengan tekstur buttery.', 22000, 18, NULL);

-- Akun awal: username admin, password admin123
