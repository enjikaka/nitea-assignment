CREATE DATABASE IF NOT EXISTS test_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE test_db;

DROP TABLE IF EXISTS products;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    price VARCHAR(50),
    categories TEXT
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO products (name, image, price, categories) VALUES
('Äpple', 'img/apple.jpg', '5.99', 'Frukt,Ekologisk'),
('Banan', 'img/banana.jpg', '4.50', 'Frukt,Ekologisk'),
('Apelsin', 'img/orange.jpg', '6.25', 'Frukt,Citrus');