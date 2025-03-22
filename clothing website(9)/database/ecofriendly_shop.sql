
CREATE DATABASE IF NOT EXISTS ecofriendly_shop;
USE ecofriendly_shop;

DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS products;

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('men', 'women', 'unisex') NOT NULL,
    image_url VARCHAR(255),
    eco_rating INT CHECK (eco_rating BETWEEN 1 AND 5),
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);


INSERT INTO products (name, description, price, category, image_url, eco_rating, stock) VALUES
('Organic Cotton T-Shirt', 'Made from 100% organic cotton, this comfortable t-shirt is perfect for everyday wear', 29.99, 'unisex', 'images/products/organic-tshirt.jpg', 5, 100),
('Recycled Denim Jeans', 'Stylish jeans created from recycled materials, saving water and reducing waste', 79.99, 'men', 'images/products/recycled-jeans.jpg', 4, 50),
('Bamboo Fiber Dress', 'Elegant dress made from sustainable bamboo fiber, perfect for any occasion', 89.99, 'women', 'images/products/bamboo-dress.jpg', 5, 30),
('Hemp Hoodie', 'Cozy hoodie made from sustainable hemp fabric, naturally antimicrobial', 69.99, 'unisex', 'images/products/hemp-hoodie.jpg', 5, 45),
('Eco Leather Jacket', 'Stylish jacket made from eco-friendly vegan leather', 129.99, 'unisex', 'images/products/eco-leather-jacket.jpg', 4, 25),
('Organic Linen Pants', 'Breathable and comfortable pants made from organic linen', 59.99, 'women', 'images/products/linen-pants.jpg', 5, 40),
('Recycled Polyester Jacket', 'Weather-resistant jacket made from recycled plastic bottles', 99.99, 'men', 'images/products/recycled-jacket.jpg', 4, 35),
('Cork Sandals', 'Comfortable sandals made from sustainable cork material', 49.99, 'unisex', 'images/products/cork-sandals.jpg', 5, 60);


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