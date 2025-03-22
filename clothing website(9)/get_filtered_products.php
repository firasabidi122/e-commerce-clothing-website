<?php
require_once 'config/db_connect.php';

header('Content-Type: application/json');

// Get filter parameters
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$price = isset($_GET['price']) ? mysqli_real_escape_string($conn, $_GET['price']) : '';
$sort = isset($_GET['sort']) ? mysqli_real_escape_string($conn, $_GET['sort']) : 'newest';

// Build the query
$sql = "SELECT * FROM products WHERE 1=1";

// Add category filter
if (!empty($category)) {
    $sql .= " AND category = '$category'";
}

// Add price filter
if (!empty($price)) {
    $price_range = explode('-', $price);
    if (count($price_range) == 2) {
        $min = $price_range[0];
        $max = $price_range[1];
        $sql .= " AND price BETWEEN $min AND $max";
    } elseif ($price == '200+') {
        $sql .= " AND price >= 200";
    }
}

// Add sorting
switch ($sort) {
    case 'price-low':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price-high':
        $sql .= " ORDER BY price DESC";
        break;
    case 'newest':
    default:
        $sql .= " ORDER BY created_at DESC";
        break;
}

$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image_url' => $row['image_url'],
            'category' => $row['category'],
            'description' => $row['description']
        ];
    }
}

echo json_encode($products);
$conn->close(); 