<?php
require_once 'config/db_connect.php';

header('Content-Type: application/json');

try {
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $price_range = isset($_POST['price']) ? trim($_POST['price']) : '';
    $sort = isset($_POST['sort']) ? trim($_POST['sort']) : 'newest';
    
    $where_conditions = [];
    $params = [];
    $types = '';
    
    // Category filter
    if (!empty($category)) {
        $where_conditions[] = "category = ?";
        $params[] = $category;
        $types .= 's';
    }
    
    // Price range filter
    if (!empty($price_range)) {
        $price_parts = explode('-', $price_range);
        if (count($price_parts) === 2) {
            $where_conditions[] = "price BETWEEN ? AND ?";
            $params[] = floatval($price_parts[0]);
            $params[] = floatval($price_parts[1]);
            $types .= 'dd';
        } elseif ($price_range === '200+') {
            $where_conditions[] = "price >= ?";
            $params[] = 200.00;
            $types .= 'd';
        }
    }
    
    // Build the query
    $query = "SELECT id, name, price, image_url, category, description, stock_quantity FROM products";
    
    if (!empty($where_conditions)) {
        $query .= " WHERE " . implode(" AND ", $where_conditions);
    }
    
    // Add sorting
    switch ($sort) {
        case 'price_low':
            $query .= " ORDER BY price ASC";
            break;
        case 'price_high':
            $query .= " ORDER BY price DESC";
            break;
        case 'name_asc':
            $query .= " ORDER BY name ASC";
            break;
        case 'name_desc':
            $query .= " ORDER BY name DESC";
            break;
        default:
            $query .= " ORDER BY created_at DESC";
    }
    
    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
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
            'description' => $row['description'],
            'stock_quantity' => $row['stock_quantity']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error filtering products'
    ]);
}

$conn->close(); 