<?php
require_once '../config/db_connect.php';

header('Content-Type: application/json');

try {
    $query = isset($_GET['q']) ? $_GET['q'] : '';

    if (strlen($query) >= 2) {
        $search = "%{$query}%";
        $stmt = $conn->prepare("SELECT id, name, price, image_url, description 
                               FROM products 
                               WHERE name LIKE ? OR description LIKE ? 
                               LIMIT 10");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => number_format($row['price'], 2),
                'image' => $row['image_url'],
                'description' => substr($row['description'], 0, 100) . '...'
            ];
        }
        
        echo json_encode([
            'success' => true,
            'results' => $products
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'results' => []
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'results' => []
    ]);
}

$conn->close(); 