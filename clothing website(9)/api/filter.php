<?php
require_once '../config/db_connect.php';

header('Content-Type: application/json');

try {
    $category = isset($_GET['categoryFilter']) ? $_GET['categoryFilter'] : '';
    $price = isset($_GET['priceFilter']) ? $_GET['priceFilter'] : '';
    $sort = isset($_GET['sortFilter']) ? $_GET['sortFilter'] : 'newest';

    $where = [];
    $params = [];
    $types = "";

    if ($category) {
        $where[] = "category = ?";
        $params[] = $category;
        $types .= "s";
    }

    if ($price) {
        $price_range = explode('-', $price);
        if (count($price_range) == 2) {
            $where[] = "price BETWEEN ? AND ?";
            $params[] = (float)$price_range[0];
            $params[] = (float)$price_range[1];
            $types .= "dd";
        } elseif ($price == "200+") {
            $where[] = "price >= ?";
            $params[] = 200.00;
            $types .= "d";
        }
    }

    $sql = "SELECT id, name, description, price, image_url, category FROM products";
    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    switch ($sort) {
        case 'price-low':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price-high':
            $sql .= " ORDER BY price DESC";
            break;
        default:
            $sql .= " ORDER BY id DESC";
    }

    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    if (!$result) {
        throw new Exception("Database query failed: " . $conn->error);
    }

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'image_url' => $row['image_url'],
            'category' => $row['category']
        ];
    }

    echo json_encode([
        'success' => true,
        'products' => $products,
        'message' => 'Products filtered successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'products' => []
    ]);
}

$conn->close(); 