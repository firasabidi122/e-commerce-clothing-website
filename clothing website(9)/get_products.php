<?php
require_once 'config/db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$price_range = isset($_GET['price_range']) ? $_GET['price_range'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

$where_clauses = [];
$params = [];
$param_types = '';

if ($search) {
    $where_clauses[] = "(name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $param_types .= 'ss';
}

if ($category) {
    $where_clauses[] = "category = ?";
    $params[] = $category;
    $param_types .= 's';
}

if ($price_range) {
    $price_parts = explode('-', $price_range);
    if (count($price_parts) == 2) {
        $where_clauses[] = "price BETWEEN ? AND ?";
        $params[] = floatval($price_parts[0]);
        $params[] = floatval($price_parts[1]);
        $param_types .= 'dd';
    } elseif ($price_range == '200+') {
        $where_clauses[] = "price >= ?";
        $params[] = 200;
        $param_types .= 'd';
    }
}

$where_sql = count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

$order_by = match($sort) {
    'price-low' => 'ORDER BY price ASC',
    'price-high' => 'ORDER BY price DESC',
    'newest' => 'ORDER BY created_at DESC',
    default => 'ORDER BY created_at DESC'
};

$sql = "SELECT * FROM products $where_sql $order_by";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode(['products' => $products]); 