<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Create connection without database
    $conn = new mysqli($host, $username, $password);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Read and execute SQL file
    $sql = file_get_contents('database.sql');
    
    // Split SQL commands by semicolon
    $commands = array_filter(array_map('trim', explode(';', $sql)), 'strlen');
    
    // Execute each command
    foreach ($commands as $command) {
        if ($conn->query($command) === FALSE) {
            throw new Exception("Error executing SQL: " . $conn->error . "\nCommand: " . $command);
        }
    }
    
    echo "Database setup completed successfully!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
