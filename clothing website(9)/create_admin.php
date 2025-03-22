<?php
require_once 'config/db_connect.php';

$name = "Admin";
$email = "admin@example.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$is_admin = 1;

$stmt = $conn->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $name, $email, $password, $is_admin);

if ($stmt->execute()) {
    echo "Admin user created successfully!<br>";
    echo "Email: admin@example.com<br>";
    echo "Password: admin123";
} else {
    echo "Error creating admin user: " . $conn->error;
}
?> 