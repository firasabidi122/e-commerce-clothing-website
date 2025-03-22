<?php
require_once 'config/db_connect.php';

// SQL statements to update the orders table
$sql = "
-- Add new columns to existing orders table
ALTER TABLE orders 
    ADD COLUMN payment_status VARCHAR(50) AFTER payment_id,
    ADD COLUMN payer_email VARCHAR(255) AFTER payment_status,
    ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;
";

// Execute the SQL
try {
    if ($conn->multi_query($sql)) {
        echo "Orders table updated successfully!<br>";
        echo "<a href='index.php'>Return to homepage</a>";
    } else {
        throw new Exception("Error updating orders table: " . $conn->error);
    }
} catch (Exception $e) {
    // If columns already exist, this is fine
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Table already has the required columns.<br>";
        echo "<a href='index.php'>Return to homepage</a>";
    } else {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "<a href='index.php'>Return to homepage</a>";
    }
}

$conn->close();
?> 