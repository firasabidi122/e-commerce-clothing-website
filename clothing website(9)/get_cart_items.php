<?php
session_start();
require_once 'config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to view cart'
    ]);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Get cart items with product details
    $query = "SELECT c.id as cart_id, c.quantity, 
              p.id as product_id, p.name, p.price, p.image_url, p.stock_quantity
              FROM cart c
              JOIN products p ON c.product_id = p.id
              WHERE c.user_id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    $total = 0;
    
    while ($row = $result->fetch_assoc()) {
        $item = [
            'cart_id' => $row['cart_id'],
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'image_url' => $row['image_url'],
            'stock_quantity' => $row['stock_quantity'],
            'subtotal' => $row['price'] * $row['quantity']
        ];
        
        $items[] = $item;
        $total += $item['subtotal'];
    }
    
    echo json_encode([
        'success' => true,
        'items' => $items,
        'total' => $total,
        'itemCount' => count($items)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 