<?php
require_once '../config/db_connect.php';
header('Content-Type: application/json');

// Get filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query
$query = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

// Add search condition
if ($search) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

// Add category condition
if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

// Add price range condition
if ($price) {
    switch ($price) {
        case '0-50':
            $query .= " AND price <= 50";
            break;
        case '50-100':
            $query .= " AND price > 50 AND price <= 100";
            break;
        case '100+':
            $query .= " AND price > 100";
            break;
    }
}

// Add sorting
switch ($sort) {
    case 'price-low':
        $query .= " ORDER BY price ASC";
        break;
    case 'price-high':
        $query .= " ORDER BY price DESC";
        break;
    case 'name':
        $query .= " ORDER BY name ASC";
        break;
    default:
        $query .= " ORDER BY created_at DESC";
}

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return results
echo json_encode([
    'success' => true,
    'products' => $products
]);

$conn->close();
?>
