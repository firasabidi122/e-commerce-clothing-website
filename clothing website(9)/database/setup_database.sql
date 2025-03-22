CREATE DATABASE IF NOT EXISTS ecofriendly_shop;
USE ecofriendly_shop;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Men', 'Eco-friendly fashion for men - Sustainable and stylish clothing designed for the modern man'),
('Women', 'Sustainable fashion for women - Beautiful and eco-conscious clothing for every occasion'),
('Unisex', 'Gender-neutral eco-friendly clothing - Versatile and sustainable fashion for everyone');

-- Insert sample products
INSERT INTO products (name, description, price, stock, image_url, category_id) VALUES
('Organic Cotton T-Shirt', 'Made from 100% organic cotton, this comfortable t-shirt is perfect for everyday wear', 29.99, 100, 'images/products/organic-tshirt.jpg', 3),
('Recycled Denim Jeans', 'Stylish jeans created from recycled materials, saving water and reducing waste', 79.99, 50, 'images/products/recycled-jeans.jpg', 1),
('Bamboo Fiber Dress', 'Elegant dress made from sustainable bamboo fiber, perfect for any occasion', 89.99, 30, 'images/products/bamboo-dress.jpg', 2),
('Hemp Hoodie', 'Cozy hoodie made from sustainable hemp fabric, naturally antimicrobial', 69.99, 45, 'images/products/hemp-hoodie.jpg', 3),
('Eco Leather Jacket', 'Stylish jacket made from eco-friendly vegan leather', 129.99, 25, 'images/products/eco-leather-jacket.jpg', 3),
('Organic Linen Pants', 'Breathable and comfortable pants made from organic linen', 59.99, 40, 'images/products/linen-pants.jpg', 2),
('Recycled Polyester Jacket', 'Weather-resistant jacket made from recycled plastic bottles', 99.99, 35, 'images/products/recycled-jacket.jpg', 1),
('Cork Sandals', 'Comfortable sandals made from sustainable cork material', 49.99, 60, 'images/products/cork-sandals.jpg', 3);

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, email, password, is_admin) 
VALUES ('Admin', 'admin@example.com', 'admin123', 1);
-- Insert admin user with new fields
INSERT INTO users (
    user_id, 
    name, 
    email, 
    password, 
    status, 
    is_admin, 
    total_amount
) VALUES (
    'USR001', 
    'Admin', 
    'admin@example.com', 
    '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 
    'active', 
    1, 
    0.00
);

-- Insert sample users
INSERT INTO users (
    user_id, 
    name, 
    email, 
    password, 
    status, 
    phone, 
    address
) VALUES 
('USR002', 'John Doe', 'john@example.com', '" . password_hash('user123', PASSWORD_DEFAULT) . "', 'active', '+216 28 882 552', 'tunisie, lafayette'),
('USR003', 'Jane Smith', 'jane@example.com', '" . password_hash('user123', PASSWORD_DEFAULT) . "', 'active', '+216 28 882 553', 'tunisie, lafayette');