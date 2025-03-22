<?php
session_start();
require_once 'config/db_connect.php';

// Get all products with optional filtering
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="shop-page">
        <div class="shop-header">
            <h1>Our Products</h1>
            <p>Discover our sustainable and eco-friendly fashion collection</p>
        </div>

        <section class="search-filter-section">
            <div class="search-filter-container">
                <form class="search-bar" id="searchForm" action="search.php" method="GET">
                    <input type="text" id="searchInput" name="q" placeholder="Search products...">
                    <button type="submit" class="search-submit">
                        <i class="fas fa-search search-icon"></i>
                    </button>
                    <div class="search-results" id="searchResults"></div>
                </form>

                <div class="filter-options">
                    <select class="filter-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                        <option value="unisex">Unisex</option>
                    </select>

                    <select class="filter-select" id="priceFilter">
                        <option value="">Price Range</option>
                        <option value="0-50">Under DT50</option>
                        <option value="50-100">DT50 - DT100</option>
                        <option value="100-200">DT100 - DT200</option>
                        <option value="200+">Over DT200</option>
                    </select>

                    <select class="filter-select" id="sortFilter">
                        <option value="newest">Newest First</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                    </select>

                    <button type="button" id="applyFilters" class="apply-filters-btn">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </section>

        <div class="active-filters" id="activeFilters"></div>

        <section class="products-grid">
            <?php
            if ($result->num_rows > 0) {
                while($product = $result->fetch_assoc()) {
                    ?>
                    <div class="product-card">
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="product-link">
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                        </a>
                        <div class="product-info">
                            <div class="product-meta">
                                <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                                <span class="product-status">
                                    <i class="fas fa-check-circle"></i> In Stock
                                </span>
                            </div>
                            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-description">
                                <?php 
                                $description = htmlspecialchars($product['description']);
                                echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                ?>
                            </p>
                            <div class="product-features">
                                <span class="feature-tag">
                                    <i class="fas fa-leaf"></i> Eco-Friendly
                                </span>
                                <span class="feature-tag">
                                    <i class="fas fa-recycle"></i> Recycled
                                </span>
                                <span class="feature-tag">
                                    <i class="fas fa-tint"></i> Water Saving
                                </span>
                                <span class="feature-tag">
                                    <i class="fas fa-heart"></i> Sustainable
                                </span>
                            </div>
                            <div class="product-price">
                                <span class="price-amount">DT<?php echo number_format($product['price'], 2); ?></span>
                            </div>
                            <div class="product-actions">
                                <button type="button" class="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='no-products'>No products found</p>";
            }
            $conn->close();
            ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html> 