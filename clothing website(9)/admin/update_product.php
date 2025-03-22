<?php
session_start();
require_once '../config/db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;

    if ($id && $name && $price) {
        // Begin transaction
        $conn->begin_transaction();

        try {
            // Update product
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
            $stmt->bind_param("sdi", $name, $price, $id);
            
            if ($stmt->execute()) {
                // Get updated product details
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $updatedProduct = $result->fetch_assoc();
                
                // Commit transaction
                $conn->commit();
                
                echo json_encode([
                    'success' => true,
                    'product' => [
                        'id' => $updatedProduct['id'],
                        'name' => $updatedProduct['name'],
                        'price' => $updatedProduct['price'],
                        'image_url' => $updatedProduct['image_url'],
                        'description' => $updatedProduct['description']
                    ]
                ]);
            } else {
                throw new Exception("Failed to update product");
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
} 