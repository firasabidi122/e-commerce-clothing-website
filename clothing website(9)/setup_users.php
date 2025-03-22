<?php
require_once 'config/db_connect.php';

// SQL to create users table
$sql = "
-- Drop existing table if it exists
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert admin user with hashed password
INSERT INTO users (name, email, password, is_admin) VALUES 
('Admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 1);
";

try {
    if ($conn->multi_query($sql)) {
        echo "Users table created successfully!<br>";
        echo "Default admin credentials:<br>";
        echo "Email: admin@example.com<br>";
        echo "Password: admin123<br>";
        echo "<a href='admin/login.php'>Go to admin login</a>";
    } else {
        throw new Exception("Error creating users table: " . $conn->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "<a href='index.php'>Return to homepage</a>";
}

$conn->close();
?> 