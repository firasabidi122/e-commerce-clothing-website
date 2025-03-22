<?php
session_start();
require_once 'config/db_connect.php';
require_once 'includes/auth.php';

// Get category from URL
$category = isset($_GET['category']) ? $_GET['category'] : '';
$valid_categories = ['men', 'women', 'unisex'];

if (!in_array($category, $valid_categories)) {
    header('Location: index.php');
    exit();
}

// Get products for this category
$stmt = $conn->prepare("SELECT p.* FROM products p 
                       INNER JOIN categories c ON p.category_id = c.id 
                       WHERE LOWER(c.name) = LOWER(?) AND p.stock > 0");
$stmt->bind_param("s", $category);
$stmt->execute();
$products = $stmt->get_result();

// Get category details
$stmt = $conn->prepare("SELECT * FROM categories WHERE LOWER(name) = LOWER(?)");
$stmt->bind_param("s", $category);
$stmt->execute();
$category_details = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($category); ?> Products - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .category-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .category-header {
            text-align: center;
            padding: 40px 0;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('images/categories/<?php echo $category; ?>-banner.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            border-radius: 15px;
            margin-bottom: 40px;
        }

        .category-header h1 {
            font-size: 2.5em;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .category-header p {
            font-size: 1.1em;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
        }

        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group select, .filter-group input {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9em;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 1.2em;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .product-description {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.3em;
            color: #27ae60;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .add-to-cart {
            flex: 2;
            padding: 10px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .add-to-cart:hover {
            background: #27ae60;
        }

        .view-details {
            flex: 1;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .view-details:hover {
            background: #2980b9;
        }

        .stock-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            margin-bottom: 10px;
        }

        .in-stock {
            background: #e8f8f5;
            color: #27ae60;
        }

        .low-stock {
            background: #fef9e7;
            color: #f1c40f;
        }

        .no-products {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
            grid-column: 1 / -1;
        }

        @media (max-width: 768px) {
            .filters-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                width: 100%;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1><a href="index.php">EcoFriendly Fashion</a></h1>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="cart.php">Cart</a>
                <?php if (isLoggedIn()): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="category-container">
        <div class="category-header">
            <h1><?php echo ucfirst($category); ?>'s Collection</h1>
            <p><?php echo htmlspecialchars($category_details['description']); ?></p>
        </div>

        <div class="filters-section">
            <div class="filter-group">
                <label for="sort-by">Sort by:</label>
                <select id="sort-by" onchange="applyFilters()">
                    <option value="newest">Newest First</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="name-asc">Name: A to Z</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="price-range">Price Range:</label>
                <input type="number" id="price-min" placeholder="Min" min="0">
                <input type="number" id="price-max" placeholder="Max" min="0">
                <button onclick="applyFilters()" class="view-details">Apply</button>
            </div>
        </div>

        <div class="products-grid">
            <?php if ($products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="product-image">
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="stock-status <?php echo $product['stock'] > 10 ? 'in-stock' : 'low-stock'; ?>">
                                <?php echo $product['stock'] > 10 ? 'In Stock' : 'Low Stock'; ?>
                            </div>
                            <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                            <div class="product-actions">
                                <button class="add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>)">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <button class="view-details" onclick="viewDetails(<?php echo $product['id']; ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-box-open" style="font-size: 3em; color: #bdc3c7; margin-bottom: 20px;"></i>
                    <h2>No Products Found</h2>
                    <p>We're currently restocking this category. Please check back later!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function addToCart(productId) {
            fetch('api/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'add',
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart successfully!');
                } else {
                    alert(data.message || 'Error adding product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to cart');
            });
        }

        function viewDetails(productId) {
            window.location.href = `product.php?id=${productId}`;
        }

        function applyFilters() {
            const sortBy = document.getElementById('sort-by').value;
            const minPrice = document.getElementById('price-min').value;
            const maxPrice = document.getElementById('price-max').value;

            let url = new URL(window.location.href);
            url.searchParams.set('sort', sortBy);
            
            if (minPrice) url.searchParams.set('min_price', minPrice);
            if (maxPrice) url.searchParams.set('max_price', maxPrice);

            window.location.href = url.toString();
        }
    </script>
</body>
</html>
