-- Create database
CREATE DATABASE product_store;
USE product_store;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- inserting the sample admin user (password: admin123)
INSERT INTO users (username, password) VALUES
    ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- inserting the  sample products
INSERT INTO products (name, description, price, image) VALUES
    ('Guitar', 'excellence guitar with imported wooden body and maple neck', 799.99, 'guitar.jpeg'),
    ('Gaming laptop', 'High-performance laptop for work and gaming', 1999.99, 'gaming laptop.webp'),
    ('Smartphone', 'Latest smartphone with advanced features', 2250.99, 'smartphone.webp'),
    ('Airbuds', 'Wireless noise-canceling airbuds', 99.99, 'airbuds.webp');
