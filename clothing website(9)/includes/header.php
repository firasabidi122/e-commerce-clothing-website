<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<header class="site-header">
    <nav class="main-nav">
        <div class="nav-left">
            <a href="index.php" class="logo">
                <h1>EcoFriendly Fashion</h1>
            </a>
        </div>

        <div class="nav-center">
            <a href="#" class="nav-link" data-popup="home">Home</a>
            <a href="#" class="nav-link" data-popup="shop">Shop</a>
            <a href="#" class="nav-link" data-popup="about">About</a>
            <a href="#" class="nav-link" data-popup="contact">Contact</a>
        </div>

        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-menu">
                    <button class="user-menu-btn">
                        <i class="fas fa-user"></i>
                        <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    </button>
                    <div class="dropdown-menu">
                        <a href="profile.php">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                        <a href="orders.php">
                            <i class="fas fa-shopping-bag"></i> Orders
                        </a>
                        <a href="wishlist.php">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="login.php" class="btn btn-login">Login</a>
                    <a href="register.php" class="btn btn-register">Register</a>
                </div>
            <?php endif; ?>

            <div class="cart-icon">
                <a href="#" class="cart-trigger">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
                </a>
            </div>
        </div>

        <div class="nav-popups">
            <!-- Home Popup -->
            <div class="nav-popup" id="home-popup">
                <div class="popup-content">
                    <div class="popup-header">
                        <h3><i class="fas fa-home"></i> Welcome to EcoFriendly Fashion</h3>
                        <button class="close-popup"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="popup-body">
                        <div class="feature-grid">
                            <div class="feature-item">
                                <i class="fas fa-leaf"></i>
                                <h4>Sustainable Fashion</h4>
                                <p>Eco-friendly materials and ethical production practices</p>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-tshirt"></i>
                                <h4>Quality Products</h4>
                                <p>Durable and stylish clothing that lasts longer</p>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-recycle"></i>
                                <h4>Recycled Materials</h4>
                                <p>Reducing waste through innovative recycling</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop Popup -->
            <div class="nav-popup" id="shop-popup">
                <div class="popup-content">
                    <div class="popup-header">
                        <h3><i class="fas fa-shopping-bag"></i> Shop Categories</h3>
                        <button class="close-popup"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="popup-body">
                        <div class="category-grid">
                            <a href="shop.php?category=men" class="category-item">
                                <img src="images/categories/men.jpg" alt="Men's Fashion">
                                <h4>Men's Collection</h4>
                                <p>Sustainable style for modern men</p>
                            </a>
                            <a href="shop.php?category=women" class="category-item">
                                <img src="images/categories/women.jpg" alt="Women's Fashion">
                                <h4>Women's Collection</h4>
                                <p>Eco-conscious fashion for women</p>
                            </a>
                            <a href="shop.php?category=unisex" class="category-item">
                                <img src="images/categories/unisex.jpg" alt="Unisex Fashion">
                                <h4>Unisex Styles</h4>
                                <p>Gender-neutral sustainable fashion</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Popup -->
            <div class="nav-popup" id="about-popup">
                <div class="popup-content">
                    <div class="popup-header">
                        <h3><i class="fas fa-info-circle"></i> About Us</h3>
                        <button class="close-popup"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="popup-body">
                        <div class="about-content">
                            <div class="about-image">
                                <img src="images/about-us.jpg" alt="Our Store">
                            </div>
                            <div class="about-text">
                                <h4>Our Story</h4>
                                <p>EcoFriendly Fashion was founded with a simple mission: to make sustainable fashion accessible to everyone while protecting our planet.</p>
                                <div class="about-stats">
                                    <div class="stat">
                                        <span class="stat-number">5K+</span>
                                        <span class="stat-label">Happy Customers</span>
                                    </div>
                                    <div class="stat">
                                        <span class="stat-number">100%</span>
                                        <span class="stat-label">Sustainable</span>
                                    </div>
                                    <div class="stat">
                                        <span class="stat-number">50+</span>
                                        <span class="stat-label">Collections</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Popup -->
            <div class="nav-popup" id="contact-popup">
                <div class="popup-content">
                    <div class="popup-header">
                        <h3><i class="fas fa-envelope"></i> Contact Us</h3>
                        <button class="close-popup"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="popup-body">
                        <div class="contact-grid">
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <h4>Visit Us</h4>
                                        <p>123 lafayette, tunisia<br>Sustainable State, 12345</p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <h4>Call Us</h4>
                                        <p>+216 28 882 552<br>Mon-Fri: 9AM-6PM</p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <h4>Email Us</h4>
                                        <p>info@ecofriendly.com<br>support@ecofriendly.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-form">
                                <input type="text" placeholder="Your Name" required>
                                <input type="email" placeholder="Your Email" required>
                                <textarea placeholder="Your Message" required></textarea>
                                <button type="submit">Send Message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    
           
        </ul>
    </nav>

    <!-- Add this button for mobile menu -->
    <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>
</header>

<!-- Cart Popup -->
<div class="cart-popup">
    <div class="cart-popup-header">
        <h3><i class="fas fa-shopping-cart"></i> Your Cart</h3>
        <button class="close-cart">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="cart-popup-items">
        <!-- Items will be loaded dynamically -->
    </div>
    <div class="cart-popup-footer">
        <div class="cart-total">
            <span>Total Amount:</span>
            <span class="total-amount">DT0.00</span>
        </div>
        <div class="cart-actions">
            <a href="cart.php" class="btn btn-view-cart">
                <i class="fas fa-shopping-bag"></i> View Cart
            </a>
            <a href="checkout.php" class="btn btn-checkout">
                <i class="fas fa-credit-card"></i> Checkout
            </a>
        </div>
    </div>
</div>

<!-- Overlay -->
<div class="overlay"></div>

<style>
.site-header {
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.main-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 5%;
    max-width: 1400px;
    margin: 0 auto;
}

.logo h1 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin: 0;
}

.search-bar {
    width: 400px;
    position: relative;
}

.search-bar form {
    display: flex;
}

.search-bar input {
    width: 100%;
    padding: 0.8rem;
    border: 2px solid #eee;
    border-radius: 25px;
    font-size: 0.9rem;
    transition: border-color 0.3s ease;
}

.search-bar input:focus {
    outline: none;
    border-color: var(--primary-color);
}

.search-bar button {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
}

.nav-right {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.auth-buttons {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-login {
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-login:hover {
    background: var(--primary-color);
    color: white;
}

.btn-register {
    background: var(--primary-color);
    color: white;
}

.btn-register:hover {
    background: var(--secondary-color);
}

.cart-icon {
    position: relative;
    color: #333;
    text-decoration: none;
    font-size: 1.2rem;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--primary-color);
    color: white;
    font-size: 0.7rem;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-menu {
    position: relative;
}

.user-menu-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: none;
    border: none;
    cursor: pointer;
    color: #333;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border-radius: 4px;
    min-width: 200px;
    display: none;
}

.user-menu:hover .dropdown-menu {
    display: block;
}

.dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1rem;
    color: #333;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.dropdown-menu a:hover {
    background: #f8f9fa;
}

.dropdown-divider {
    height: 1px;
    background: #eee;
    margin: 0.5rem 0;
}

.category-nav {
    background: #f8f9fa;
    padding: 0.5rem 0;
    border-top: 1px solid #eee;
}

.category-nav ul {
    list-style: none;
    display: flex;
    justify-content: center;
    gap: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 5%;
}

.category-nav a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.category-nav a:hover {
    color: var(--primary-color);
}

@media (max-width: 1024px) {
    .search-bar {
        width: 300px;
    }
}

@media (max-width: 768px) {
    .main-nav {
        flex-wrap: wrap;
        gap: 1rem;
    }

    .nav-center {
        order: 3;
        width: 100%;
    }

    .search-bar {
        width: 100%;
    }

    .category-nav ul {
        gap: 1rem;
        flex-wrap: wrap;
    }
}

.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-color);
    cursor: pointer;
}

@media screen and (max-width: 768px) {
    .mobile-menu-btn {
        display: block;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    .nav-links {
        display: none;
        width: 100%;
    }

    .nav-links.active {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        padding: 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .nav-links ul {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Cart Popup Styles */
.cart-popup {
    position: fixed;
    top: 0;
    right: -400px;
    width: 100%;
    max-width: 400px;
    height: 100vh;
    background: white;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
    z-index: 1001;
    transition: right 0.3s ease;
    display: flex;
    flex-direction: column;
}

.cart-popup.active {
    right: 0;
}

.cart-popup-header {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-popup-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.close-cart {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    color: #666;
}

.cart-popup-items {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.cart-popup-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.cart-popup-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}

.cart-item-details {
    flex: 1;
}

.cart-popup-footer {
    padding: 1.5rem;
    border-top: 1px solid #eee;
    background: white;
}

.cart-total {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    font-weight: bold;
}

.cart-actions {
    display: flex;
    gap: 1rem;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.overlay.active {
    opacity: 1;
    visibility: visible;
}

.empty-cart-message {
    text-align: center;
    color: #666;
    padding: 2rem;
}

/* Cart Count Badge */
.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--primary-color);
    color: white;
    font-size: 0.7rem;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Cart Actions Buttons */
.cart-actions .btn {
    flex: 1;
    text-align: center;
    padding: 0.8rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-secondary {
    background: #f8f9fa;
    color: var(--text-color);
    border: 1px solid #ddd;
}
</style>

<!-- Add mobile menu JavaScript -->
<script>
document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
    document.querySelector('.nav-links').classList.toggle('active');
});
</script> 