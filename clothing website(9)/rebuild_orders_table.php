<?php
require_once 'config/db_connect.php';

// SQL statements to rebuild the orders table
$sql = "
-- Drop existing tables if they exist
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;

-- Create the orders table with all necessary fields
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total DECIMAL(10,2) NOT NULL,
    payment_id VARCHAR(255),
    payment_status VARCHAR(50),
    payer_email VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the order_items table
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// Execute the SQL
try {
    if ($conn->multi_query($sql)) {
        echo "Orders and order_items tables created successfully!<br>";
        echo "<a href='index.php'>Return to homepage</a>";
    } else {
        throw new Exception("Error creating tables: " . $conn->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "<a href='index.php'>Return to homepage</a>";
}

$conn->close();
?> 