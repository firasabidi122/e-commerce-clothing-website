<?php
session_start();
require_once 'config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to add items to cart'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Check if product exists and has enough stock
    $check_product = $conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
    $check_product->bind_param("i", $product_id);
    $check_product->execute();
    $result = $check_product->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Product not found");
    }

    $product = $result->fetch_assoc();
    
    if ($product['stock_quantity'] < $quantity) {
        throw new Exception("Not enough stock available");
    }

    // Check if product already exists in cart
    $check_cart = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $check_cart->bind_param("ii", $user_id, $product_id);
    $check_cart->execute();
    $cart_result = $check_cart->get_result();

    if ($cart_result->num_rows > 0) {
        // Update existing cart item
        $cart_item = $cart_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $quantity;
        
        if ($new_quantity > $product['stock_quantity']) {
            throw new Exception("Cannot add more items than available in stock");
        }
        
        $update_cart = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update_cart->bind_param("ii", $new_quantity, $cart_item['id']);
        $update_cart->execute();
    } else {
        // Add new cart item
        $add_to_cart = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $add_to_cart->bind_param("iii", $user_id, $product_id, $quantity);
        $add_to_cart->execute();
    }

    // Get updated cart count
    $count_query = $conn->prepare("SELECT SUM(quantity) as cart_count FROM cart WHERE user_id = ?");
    $count_query->bind_param("i", $user_id);
    $count_query->execute();
    $count_result = $count_query->get_result();
    $cart_count = $count_result->fetch_assoc()['cart_count'];

    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart successfully',
        'cartCount' => (int)$cart_count
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 