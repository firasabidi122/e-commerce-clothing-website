<?php
session_start();
require_once '../config/db_connect.php';

header('Content-Type: application/json');

// Initialize cart in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = ['success' => false, 'message' => '', 'cart' => []];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $product_id = isset($data['product_id']) ? (int)$data['product_id'] : 0;
        
        if ($product_id > 0) {
            // Get product details from database
            $stmt = $conn->prepare("SELECT id, name, price, image_url FROM products WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("i", $product_id);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if ($product = $result->fetch_assoc()) {
                // Add or update product in cart
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['quantity']++;
                } else {
                    $_SESSION['cart'][$product_id] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => (float)$product['price'],
                        'image_url' => $product['image_url'],
                        'quantity' => 1
                    ];
                }
                
                $response['success'] = true;
                $response['message'] = 'Product added to cart successfully';
                $response['cart'] = array_values($_SESSION['cart']);
            } else {
                throw new Exception("Product not found");
            }
            
            $stmt->close();
        } else {
            throw new Exception("Invalid product ID");
        }
    }

    // Return cart contents for GET requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $response['success'] = true;
        $response['cart'] = array_values($_SESSION['cart']);
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
$conn->close();
