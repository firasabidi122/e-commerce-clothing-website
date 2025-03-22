<?php
require_once 'config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

$search = '%' . trim($_GET['q']) . '%';

try {
    $stmt = $conn->prepare("
        SELECT id, name, price, image_url 
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
            'name' => htmlspecialchars($row['name']),
            'price' => $row['price'],
            'image_url' => htmlspecialchars($row['image_url'])
        ];
    }
    
    echo json_encode($products);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while searching']);
}

$stmt->close();
$conn->close(); 