CREATE DATABASE IF NOT EXISTS test_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE test_db;

-- Drop tables in reverse order to avoid foreign key constraints
DROP TABLE IF EXISTS product_categories;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create products table (removed categories column)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create junction table for many-to-many relationship
CREATE TABLE IF NOT EXISTS product_categories (
    product_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (product_id, category_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insert sample categories
INSERT INTO categories (name) VALUES
('Fairtrade'),
('Ekologisk'),
('Citrus');

-- Insert sample products
INSERT INTO products (name, image, price) VALUES
('Äpple', 'img/apple.jpg', 5.99),
('Banan', 'img/banana.jpg', 4.50),
('Apelsin', 'img/orange.jpg', 6.25);

-- Link products to categories
INSERT INTO product_categories (product_id, category_id) VALUES
(1, 2), -- Äpple -> Ekologisk
(2, 1), -- Banan -> Fairtrade
(2, 2), -- Banan -> Ekologisk
(3, 3); -- Apelsin -> Citrus
