<?php
session_start();
require_once 'config/db_connect.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        .cart-popup {
            position: fixed;
            top: 80px;
            right: 20px;
            width: 400px;
            max-width: 90vw;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            flex-direction: column;
            max-height: calc(100vh - 120px);
            display: none;
        }

        .cart-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border-radius: 8px 8px 0 0;
        }

        .cart-items-wrapper {
            flex: 1;
            overflow: hidden;
        }

        .cart-items {
            padding: 10px;
            overflow-y: auto;
            max-height: 400px; 
        }

        .cart-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            position: relative;
            align-items: center;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            background: #f9f9f9;
            border: 1px solid #eee;
        }

        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .cart-item-image:hover img {
            transform: scale(1.1);
        }

        .cart-item-details {
            flex: 1;
            min-width: 0;
            padding-right: 25px;
        }

        .cart-item-details h4 {
            margin: 0 0 0.5rem;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #333;
        }

        .cart-item-price {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .quantity-btn {
            background: #f5f5f5;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: #666;
        }

        .quantity-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .remove-item {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 0.25rem;
            transition: all 0.2s ease;
        }

        .remove-item:hover {
            color: #ff4444;
            transform: scale(1.1);
        }

        .cart-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background: #fff;
            border-radius: 0 0 8px 8px;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .cart-buttons {
            display: flex;
            gap: 10px;
        }

        .cart-buttons a {
            flex: 1;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .view-cart-btn {
            background: #f5f5f5;
            color: #333;
        }

        .checkout-btn {
            background: var(--primary-color);
            color: #fff;
        }

        .view-cart-btn:hover {
            background: #e0e0e0;
        }

        .checkout-btn:hover {
            background: var(--primary-color-dark);
        }

        
        .cart-items::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .cart-items::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        
        .cart-items {
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
        }

        .cart-popup.active {
            display: flex;
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .empty-cart i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
            display: block;
        }

        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-menu-btn {
            background: none;
            border: none;
            color: var(--text-color);
            padding: 8px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .user-menu-btn i {
            font-size: 1.1rem;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-width: 200px;
            display: none;
            z-index: 1000;
        }

        .user-menu:hover .user-dropdown {
            display: block;
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: var(--text-color);
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .user-dropdown a:hover {
            background-color: #f5f5f5;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #eee;
            margin: 8px 0;
        }

        .logout-btn {
            color: #ff4444 !important;
        }

        .logout-btn:hover {
            background-color: #fff5f5 !important;
        }

        .user-dropdown i {
            width: 20px;
            text-align: center;
        }

        
        .added {
            background-color: #dff0d8;
            border-color: #d6e9c6;
            color: #3c763d;
        }

        .add-to-cart-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            justify-content: center;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .add-to-cart-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .add-to-cart-btn.added {
            background: #2ecc71;
        }

        .add-to-cart-btn.added::after {
            content: '✓';
            position: absolute;
            right: 1rem;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        
        .search-filter-wrapper {
            padding: 30px 0;
            background: linear-gradient(135deg, rgba(236, 253, 245, 0.8), rgba(209, 250, 229, 0.8));
            margin-top: 80px;
            border-bottom: 1px solid rgba(52, 211, 153, 0.2);
            backdrop-filter: blur(10px);
        }

        .search-filter-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(8px);
        }

        .search-bar {
            position: relative;
            width: 100%;
            transition: all 0.3s ease;
        }

        .search-bar input {
            width: 100%;
            padding: 1.2rem 1.2rem 1.2rem 3.5rem;
            border: 2px solid rgba(52, 211, 153, 0.2);
            border-radius: 50px;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            color: #2d3748;
        }

        .search-bar input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.15);
            background: white;
            outline: none;
        }

        .search-bar input::placeholder {
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .search-bar input:focus::placeholder {
            opacity: 0.7;
            transform: translateX(10px);
        }

        .search-icon {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .search-bar:focus-within .search-icon {
            transform: translateY(-50%) scale(1.1);
            color: var(--secondary-color);
        }

        
        .filter-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            padding-top: 0.5rem;
        }

        .filter-select {
            flex: 1;
            min-width: 180px;
            padding: 0.8rem 2.5rem 0.8rem 1.2rem;
            border: 2px solid rgba(52, 211, 153, 0.2);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            color: #2d3748;
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%234CAF50' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        .filter-select:hover {
            border-color: var(--primary-color);
            background-color: white;
            transform: translateY(-1px);
        }

        .filter-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.15);
            background-color: white;
            outline: none;
        }

        .apply-filters-btn {
            padding: 0.8rem 2rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(52, 211, 153, 0.2);
        }

        .apply-filters-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(52, 211, 153, 0.3);
        }

        .apply-filters-btn i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .apply-filters-btn:hover i {
            transform: rotate(180deg);
        }

        
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-top: 1rem;
            padding: 0 2rem;
        }

        .active-filter {
            background: rgba(52, 211, 153, 0.1);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid rgba(52, 211, 153, 0.2);
        }

        @media (max-width: 768px) {
            .search-filter-wrapper {
                padding: 20px 15px;
                margin-top: 60px;
            }

            .search-filter-container {
                padding: 1.5rem;
            }

            .search-bar input {
                padding: 1rem 1rem 1rem 3rem;
                font-size: 1rem;
            }

            .filter-options {
                flex-direction: column;
            }

            .filter-select {
                width: 100%;
            }

            .apply-filters-btn {
                width: 100%;
                justify-content: center;
            }
        }

        
        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 1rem;
        }

        .footer-section ul li a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: rgba(76, 175, 80, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .footer-section ul li a i {
            color: rgba(255, 255, 255, 0.9);
        }

        .footer-section ul li a:hover {
            background: rgba(76, 175, 80, 0.25);
            color: white;
            transform: translateX(5px);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .footer-section ul li a:hover:before {
            transform: scale(1.5);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        
        .footer-section {
            flex: 1;
            min-width: 250px;
            padding: 1.5rem;
        }

        .footer-section h3 {
            color: white;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 0.8rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .footer-section h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

       
        .footer-section h3:hover {
            transform: translateX(5px);
            color: rgba(255, 255, 255, 0.95);
        }

        .footer-section h3:hover:after {
            width: 100%;
            background: var(--primary-color);
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.3);
        }

        
        @media (max-width: 768px) {
            .footer-section ul li a {
                padding: 0.6rem 1rem;
            }
            
            .footer-section {
                min-width: 200px;
            }
        }

        
        .social-links {
            display: flex;
            gap: 1.2rem;
            margin-top: 1rem;
        }

        .social-link {
            position: relative;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .social-link:hover {
            color: white;
            transform: translateY(-3px);
            background: var(--primary-color);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .social-hover {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .social-link:hover .social-hover {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

       
        .social-link .fa-instagram:hover {
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .social-link .fa-facebook:hover {
            color: #4267B2;
        }

        .social-link .fa-tiktok:hover {
            color: #000000;
        }

        .social-link .fa-pinterest:hover {
            color: #E60023;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1><a href="index.php">EcoFriendly Fashion</a></h1>
            </div>
            
            <div class="nav-center">
                
                <a href="blog.php" class="nav-link">Blog</a>
                <a href="pages/faq.php" class="nav-link">FAQ</a>
                <a href="pages/contact.php" class="nav-link">Contact</a>
            </div>

            <div class="nav-right">
                <?php if ($is_logged_in): ?>
                    <div class="user-menu">
                        <button class="user-menu-btn">
                            <i class="fas fa-user"></i>
                            <span><?php echo htmlspecialchars($user_name); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown">
                           
                            <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <a href="login.php" class="btn btn-login"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <a href="register.php" class="btn btn-register"><i class="fas fa-user-plus"></i> Register</a>
                    </div>
                <?php endif; ?>
                
                <div class="cart-icon">
                    <a href="#" class="cart-trigger">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Cart Popup -->
        <div class="cart-popup">
            <div class="cart-header">
                <h3><i class="fas fa-shopping-basket"></i> Shopping Cart</h3>
                <button class="close-cart"><i class="fas fa-times"></i></button>
            </div>
            <div class="cart-items-wrapper">
                <div class="cart-items">
                    
                </div>
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total:</span>
                    <span class="total-amount">DT0.00</span>
                </div>
                <div class="cart-buttons">
                    <a href="cart.php" class="view-cart-btn">
                        <i class="fas fa-shopping-cart"></i>
                        View Cart
                    </a>
                    <?php if ($is_logged_in): ?>
                    <a href="checkout.php" class="checkout-btn">
                        <i class="fas fa-shopping-bag"></i>
                        Proceed to Checkout
                    </a>
                    <?php else: ?>
                        <a href="login.php" class="checkout-btn disabled" title="Please login to checkout">
                            <i class="fas fa-lock"></i>
                            Login to Checkout
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="notifications"></div>

        <template id="cart-item-template">
            <div class="cart-item" data-id="">
                <div class="item-image">
                    <img src="" alt="">
                </div>
                <div class="item-details">
                    <h4 class="item-name"></h4>
                    <div class="item-price"></div>
                    <div class="item-controls">
                        <div class="quantity-controls">
                            <button class="quantity-btn minus"><i class="fas fa-minus"></i></button>
                            <input type="number" class="quantity-input" value="1" min="1" max="99">
                            <button class="quantity-btn plus"><i class="fas fa-plus"></i></button>
                        </div>
                        <button class="remove-item"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>
        </template>
    </header>

    <div class="search-filter-wrapper">
        <div class="search-filter-section">
            <div class="search-filter-container">
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" placeholder="Search products...">
                    </div>
                
                <div class="filter-options">
                    <select id="categoryFilter" class="filter-select">
                            <option value="">All Categories</option>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                            <option value="unisex">Unisex</option>
                        </select>

                    <select id="priceFilter" class="filter-select">
                            <option value="">Price Range</option>
                            <option value="0-50">Under DT50</option>
                            <option value="50-100">DT50 - DT100</option>
                            <option value="100-200">DT100 - DT200</option>
                            <option value="200+">Over DT200</option>
                        </select>

                    <select id="sortFilter" class="filter-select">
                            <option value="newest">Newest First</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                        </select>

                    <button id="applyFilters" class="apply-filters-btn">
                        <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>
        </div>
    </div>

    <main>
        
            </div>
        </section>

        <section class="featured-products">
            <h2>Featured Products</h2>
            <div class="active-filters" id="activeFilters"></div>
            <div class="product-grid">
                <?php
                require_once 'config/db_connect.php';
                
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($product = $result->fetch_assoc()) {
                        ?>
                        <div class="product-card" onclick="window.location.href='product-details.php?id=${product.id}'">
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
                                <div class="product-price">DT<?php echo number_format($product['price'], 2); ?></div>
                                <button class="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-price="<?php echo number_format($product['price'], 2); ?>"
                                    data-image="<?php echo htmlspecialchars($product['image_url']); ?>"
                                    onclick="event.stopPropagation()">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No products found</p>";
                }
                $conn->close();
                ?>
            </div>
        </section>

        <section class="categories">
            <h2>Shop by Category</h2>
            <div class="category-grid">
                <div class="category-card">
                    <img src="images/categories/women.jpg" alt="Women's Fashion">
                    <h3>Women's Fashion</h3>
                    <a href="category.php?cat=women" class="category-link">Shop Now</a>
                </div>
                <div class="category-card">
                    <img src="images/categories/men.jpeg" alt="Men's Fashion">
                    <h3>Men's Fashion</h3>
                    <a href="category.php?cat=men" class="category-link">Shop Now</a>
                </div>
                <div class="category-card">
                    <img src="images/categories/unisex.jpeg" alt="Unisex Fashion">
                    <h3>Unisex Collection</h3>
                    <a href="category.php?cat=unisex" class="category-link">Shop Now</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>EcoFriendly Fashion is committed to sustainable and ethical fashion. We believe in making a positive impact on the environment while keeping you stylish.</p>
                <div class="footer-social">
                    <a href="https://www.instagram.com/ecofashionstore69/" target="_blank" class="social-icon" title="Follow us on Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                   
                   
                    </a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Quick Links</h3>
                <div class="footer-links">
                    
                    
                    <a href="sustainability.php"><i class="fas fa-chevron-right"></i> Sustainability</a>
                    
                </div>
            </div>

            <div class="footer-section">
                <h3>Customer Service</h3>
                <ul>
                    <li>
                        <a href="pages/returns.php">
                            <i class="fas fa-exchange-alt"></i>
                            Returns & Exchanges
                        </a>
                    </li>
                    <li>
                        <a href="pages/contact.php">
                            <i class="fas fa-envelope"></i>
                            Contact Us
                        </a>
                    </li>
                </ul>
                </div>

            
            </div>

           
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> EcoFriendly Fashion. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cart popup functionality
            const cartTrigger = document.querySelector('.cart-trigger');
            const cartPopup = document.querySelector('.cart-popup');
            const closeCart = document.querySelector('.close-cart');
            
            function showCartPopup() {
                cartPopup.style.display = 'block';
                // Add class for animation if needed
                cartPopup.classList.add('active');
            }
            
            function hideCartPopup() {
                cartPopup.classList.remove('active');
                cartPopup.style.display = 'none';
            }
            
            cartTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                showCartPopup();
                updateCartPopup();
            });
            
            closeCart.addEventListener('click', hideCartPopup);
            
            // Function to update cart popup contents
            async function updateCartPopup() {
                try {
                    const response = await fetch('cart.php?action=get_cart_items');
                    const data = await response.json();
                    
                    const cartItemsContainer = document.querySelector('.cart-items');
                    const totalAmount = document.querySelector('.total-amount');
                    const checkoutBtn = document.querySelector('.checkout-btn');
                    
                    cartItemsContainer.innerHTML = '';
                    let total = 0;
                    
                    if (data.items && data.items.length > 0) {
                        data.items.forEach(item => {
                            const itemTotal = item.price * item.quantity;
                            total += itemTotal;
                            
                            cartItemsContainer.innerHTML += `
                                <div class="cart-item">
                                    <div class="cart-item-image">
                                        <img src="${item.image_url}" alt="${item.name}">
                                    </div>
                                    <div class="cart-item-details">
                                        <h4>${item.name}</h4>
                                        <div class="cart-item-price">DT${item.price.toFixed(2)}</div>
                                        <div class="cart-item-quantity">
                                            <button class="quantity-btn minus" data-product-id="${item.id}">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span>${item.quantity}</span>
                                            <button class="quantity-btn plus" data-product-id="${item.id}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button class="remove-item" data-product-id="${item.id}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                        });
                        
                        totalAmount.textContent = `DT${total.toFixed(2)}`;
                        
                        if (checkoutBtn && !checkoutBtn.classList.contains('disabled')) {
                            checkoutBtn.style.display = 'block';
                        }
                    } else {
                        cartItemsContainer.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
                        totalAmount.textContent = 'DT0.00';
                        
                        if (checkoutBtn) {
                            checkoutBtn.style.display = 'none';
                        }
                    }
                    
                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    cartCount.textContent = data.total_items || 0;
                } catch (error) {
                    console.error('Error updating cart:', error);
                }
            }
            
            // Add to Cart functionality
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const productId = this.dataset.productId;
                    
                    try {
                        const response = await fetch('cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `add_to_cart=1&product_id=${productId}&quantity=1`
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Update cart popup and show it
                            await updateCartPopup();
                            showCartPopup();
                        } else {
                            alert(data.message || 'Error adding product to cart');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error adding product to cart');
                    }
                });
            });
            
            // Handle quantity changes and remove items
            document.querySelector('.cart-items').addEventListener('click', async function(e) {
                const target = e.target.closest('button'); // Use closest to handle icon clicks
                if (!target) return;
                
                if (target.classList.contains('quantity-btn')) {
                    const productId = target.dataset.productId;
                    const isIncrease = target.classList.contains('plus');
                    const quantitySpan = target.parentElement.querySelector('span');
                    const currentQuantity = parseInt(quantitySpan.textContent);
                    const newQuantity = isIncrease ? currentQuantity + 1 : Math.max(1, currentQuantity - 1);
                    
                    try {
                        const response = await fetch('cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `update_quantity=1&product_id=${productId}&quantity=${newQuantity}`
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            // Update the quantity display immediately
                            quantitySpan.textContent = newQuantity;
                            // Update the entire cart to refresh totals
                            await updateCartPopup();
                        } else {
                            alert(data.message || 'Error updating quantity');
                        }
                    } catch (error) {
                        console.error('Error updating quantity:', error);
                        alert('Error updating quantity');
                    }
                }
                
                if (target.classList.contains('remove-item')) {
                    const productId = target.dataset.productId;
                    
                    try {
                        const response = await fetch('cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `remove_from_cart=1&product_id=${productId}`
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            await updateCartPopup();
                        } else {
                            alert(data.message || 'Error removing item');
                        }
                    } catch (error) {
                        console.error('Error removing item:', error);
                        alert('Error removing item');
                    }
                }
            });

            // Search and Filter Functionality
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const priceFilter = document.getElementById('priceFilter');
            const sortFilter = document.getElementById('sortFilter');
            const applyFiltersBtn = document.getElementById('applyFilters');
            const productGrid = document.querySelector('.product-grid');
            const activeFilters = document.getElementById('activeFilters');

            function updateProducts() {
                const searchTerm = searchInput.value;
                const category = categoryFilter.value;
                const priceRange = priceFilter.value;
                const sortBy = sortFilter.value;

                // Create URL with filter parameters
                const params = new URLSearchParams({
                    search: searchTerm,
                    category: category,
                    price_range: priceRange,
                    sort: sortBy
                });

                // Fetch filtered products
                fetch(`get_products.php?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        productGrid.innerHTML = ''; // Clear current products

                        if (data.products && data.products.length > 0) {
                            data.products.forEach(product => {
                                const productCard = `
                                    <div class="product-card" onclick="window.location.href='product-details.php?id=${product.id}'">
                                            <div class="product-image">
                                                <img src="${product.image_url}" alt="${product.name}">
                                            </div>
                                        <div class="product-info">
                                            <h3 class="product-title">${product.name}</h3>
                                            <p class="product-description">${product.description.substring(0, 100)}...</p>
                                            <div class="product-price">DT${parseFloat(product.price).toFixed(2)}</div>
                                            <button class="add-to-cart-btn" 
                                                data-product-id="${product.id}"
                                                data-name="${product.name}"
                                                data-price="${product.price}"
                                                data-image="${product.image_url}"
                                                onclick="event.stopPropagation()">
                                                <i class="fas fa-shopping-cart"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                `;
                                productGrid.innerHTML += productCard;
                            });

                            // Reattach event listeners to new add-to-cart buttons
                            attachAddToCartListeners();
                        } else {
                            productGrid.innerHTML = '<p class="no-products">No products found</p>';
                        }

                        updateActiveFilters();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        productGrid.innerHTML = '<p class="error">Error loading products</p>';
                    });
            }

            // Create a separate function for attaching event listeners
            function attachAddToCartListeners() {
                document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                    button.addEventListener('click', async function() {
                        const productId = this.dataset.productId;
                        
                        try {
                            const response = await fetch('cart.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `add_to_cart=1&product_id=${productId}&quantity=1`
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                // Add visual feedback
                                this.classList.add('added');
                                setTimeout(() => this.classList.remove('added'), 1000);
                                
                                // Update cart popup
                                const cartResponse = await fetch('cart.php?get_cart=1');
                                const cartData = await cartResponse.json();
                                
                                if (cartData.success) {
                                    const cartItems = document.querySelector('.cart-items');
                                    const cartTotal = document.querySelector('.total-amount');
                                    
                                    // Update cart items HTML
                                    cartItems.innerHTML = cartData.items.map(item => `
                                        <div class="cart-item">
                                            <div class="cart-item-image">
                                                <img src="${item.image_url}" alt="${item.name}">
                                            </div>
                                            <div class="cart-item-details">
                                                <h4>${item.name}</h4>
                                                <div class="cart-item-price">DT${parseFloat(item.price).toFixed(2)}</div>
                                                <div class="cart-item-quantity">
                                                    <button class="quantity-btn minus" data-product-id="${item.id}">-</button>
                                                    <span>${item.quantity}</span>
                                                    <button class="quantity-btn plus" data-product-id="${item.id}">+</button>
                                                </div>
                                            </div>
                                            <button class="remove-item" data-product-id="${item.id}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    `).join('');
                                    
                                    // Update total
                                    cartTotal.textContent = `DT${parseFloat(cartData.total).toFixed(2)}`;
                                    
                                    // Show cart popup
                                    showCartPopup();
                                }
                            } else {
                                alert(data.message || 'Error adding product to cart');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Error adding product to cart');
                        }
                    });
                    });
            }

            function updateActiveFilters() {
                const activeFiltersList = [];
                
                if (searchInput.value) {
                    activeFiltersList.push(`Search: "${searchInput.value}"`);
                }
                if (categoryFilter.value) {
                    activeFiltersList.push(`Category: ${categoryFilter.options[categoryFilter.selectedIndex].text}`);
                }
                if (priceFilter.value) {
                    activeFiltersList.push(`Price: ${priceFilter.options[priceFilter.selectedIndex].text}`);
                }
                if (sortFilter.value) {
                    activeFiltersList.push(`Sort: ${sortFilter.options[sortFilter.selectedIndex].text}`);
                }

                if (activeFiltersList.length > 0) {
                    activeFilters.innerHTML = activeFiltersList.map(filter => 
                        `<span class="active-filter">${filter}</span>`
                    ).join('');
                } else {
                    activeFilters.innerHTML = '';
                }
            }

            // Event listeners
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateProducts, 500);
            });

            applyFiltersBtn.addEventListener('click', updateProducts);

            
            categoryFilter.addEventListener('change', updateProducts);
            priceFilter.addEventListener('change', updateProducts);
            sortFilter.addEventListener('change', updateProducts);

            
            const userMenu = document.querySelector('.user-menu');
            const userMenuBtn = document.querySelector('.user-menu-btn');
            const userDropdown = document.querySelector('.user-dropdown');

            if (userMenuBtn) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('active');
                });

                
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target)) {
                        userMenu.classList.remove('active');
                    }
                });

               
                const logoutBtn = document.querySelector('.logout-btn');
                if (logoutBtn) {
                    logoutBtn.addEventListener('click', function(e) {
                        if (!confirm('Are you sure you want to logout?')) {
                            e.preventDefault();
                        }
                    });
                }
            }

            // Add click handler for disabled checkout button
            const checkoutBtn = document.querySelector('.checkout-btn.disabled');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = 'login.php?redirect=checkout';
                });
            }

            
            attachAddToCartListeners();

            function updateProductCard(productData) {
                const productCard = document.querySelector(`.product-card[data-product-id="${productData.id}"]`);
                if (productCard) {
                    productCard.querySelector('.product-title').textContent = productData.name;
                    productCard.querySelector('.product-price').textContent = `DT${parseFloat(productData.price).toFixed(2)}`;
                    
                    
                    const addToCartBtn = productCard.querySelector('.add-to-cart-btn');
                    if (addToCartBtn) {
                        addToCartBtn.dataset.name = productData.name;
                        addToCartBtn.dataset.price = productData.price;
                    }
                }
            }

            const storedUpdate = localStorage.getItem('productUpdate');
            if (storedUpdate) {
                const update = JSON.parse(storedUpdate);
                
                if (Date.now() - update.timestamp < 300000) {
                    updateProductCard(update.product);
                }
            }

            // Listen for real-time updates
            window.addEventListener('storage', function(e) {
                if (e.key === 'productUpdate') {
                    const update = JSON.parse(e.newValue);
                    updateProductCard(update.product);
                }
            });

            // Clean up old updates
            window.addEventListener('beforeunload', function() {
                const storedUpdate = localStorage.getItem('productUpdate');
                if (storedUpdate) {
                    const update = JSON.parse(storedUpdate);
                    if (Date.now() - update.timestamp > 300000) {
                        localStorage.removeItem('productUpdate');
                    }
                }
            });
        });
    </script>
    <script src="js/main.js"></script>
    <script src="js/products.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>
