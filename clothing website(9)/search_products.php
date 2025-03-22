<?php
require_once 'config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

try {
    $search = '%' . trim($_GET['q']) . '%';
    
    $stmt = $conn->prepare("
        SELECT id, name, price, image_url, category, description 
        FROM products 
        WHERE name LIKE ? 
        OR description LIKE ? 
        OR category LIKE ?
        LIMIT 10
    ");
    
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image_url' => $row['image_url'],
            'category' => $row['category'],
            'description' => $row['description']
        ];
    }
    
    echo json_encode($products);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error searching products']);
}

$conn->close(); 