<?php
session_start();
require_once 'config/db_connect.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['orderID']) && isset($data['paymentDetails'])) {
    try {
        // Start transaction
        $conn->begin_transaction();

        // Get user info
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $payment_id = $data['orderID'];
        $payment_status = $data['paymentDetails']['status'];
        $payer_email = $data['paymentDetails']['payer']['email_address'];
        
        // Calculate total
        $total = 0;
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $price = $stmt->get_result()->fetch_assoc()['price'];
            $total += $price * $quantity;
        }

        // Create order
        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, total_amount, payment_id, payment_status, payer_email, status) 
            VALUES (?, ?, ?, ?, ?, 'completed')
        ");
        $stmt->bind_param("idsss", $user_id, $total, $payment_id, $payment_status, $payer_email);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Add order items
        $stmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $price_stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $price_stmt->bind_param("i", $product_id);
            $price_stmt->execute();
            $price = $price_stmt->get_result()->fetch_assoc()['price'];
            
            $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt->execute();

            // Update product stock
            $update_stock = $conn->prepare("
                UPDATE products 
                SET stock = stock - ? 
                WHERE id = ? AND stock >= ?
            ");
            $update_stock->bind_param("iii", $quantity, $product_id, $quantity);
            $update_stock->execute();
        }

        // Clear cart
        $_SESSION['cart'] = array();

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'success' => true, 
            'order_id' => $order_id
        ]);

    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        error_log('Payment Processing Error: ' . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'error' => 'An error occurred while processing your order.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'error' => 'Invalid payment data received.'
    ]);
} 