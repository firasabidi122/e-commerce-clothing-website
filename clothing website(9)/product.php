<?php
session_start();
require_once 'config/db_connect.php';


$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();


if (!$product) {
    header('Location: index.php');
    exit();
}


$stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
$stmt->bind_param("si", $product['category'], $product_id);
$stmt->execute();
$related_products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-to-shop">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Shop
        </a>
    </div>

    <main class="product-page">
        <div class="product-container">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>

            <div class="product-details">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="product-price">
                    <span class="price">DT<?php echo number_format($product['price'], 2); ?></span>
                    <?php if ($product['stock'] > 0): ?>
                        <span class="stock in-stock">In Stock</span>
                    <?php else: ?>
                        <span class="stock out-of-stock">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>

                <div class="product-category">
                    <span>Category:</span>
                    <a href="shop.php?category=<?php echo urlencode($product['category']); ?>">
                        <?php echo htmlspecialchars($product['category']); ?>
                    </a>
                </div>

                <div class="product-actions">
                    <form class="add-to-cart-form">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn minus">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="quantity-input" readonly>
                            <button type="button" class="quantity-btn plus">+</button>
                        </div>
                        <button type="submit" class="add-to-cart-btn" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                </div>

                <div class="product-features">
                    <div class="feature">
                        <i class="fas fa-truck"></i>
                        <span>Free Shipping</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-undo"></i>
                        <span>30 Days Return</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Payment</span>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($related_products->num_rows > 0): ?>
        <section class="related-products">
            <h2>Other Products</h2>
            <div class="product-grid">
                <?php while ($related = $related_products->fetch_assoc()): ?>
                    <div class="product-card">
                        <a href="product.php?id=<?php echo $related['id']; ?>" class="product-link">
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($related['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($related['name']); ?>">
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($related['name']); ?></h3>
                                <p class="product-price">DT<?php echo number_format($related['price'], 2); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.add-to-cart-form');
            const quantityInput = form.querySelector('.quantity-input');
            const minusBtn = form.querySelector('.quantity-btn.minus');
            const plusBtn = form.querySelector('.quantity-btn.plus');
            const maxStock = <?php echo $product['stock']; ?>;

            // Quantity controls
            minusBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value > 1) {
                    quantityInput.value = value - 1;
                }
            });

            plusBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value < maxStock) {
                    quantityInput.value = value + 1;
                }
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                fetch('cart.php', {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        const notification = document.createElement('div');
                        notification.className = 'notification success';
                        notification.innerHTML = '<i class="fas fa-check-circle"></i> Product added to cart!';
                        document.body.appendChild(notification);

                        // Update cart popup
                        updateCartPopup();

                        // Remove notification after 3 seconds
                        setTimeout(() => {
                            notification.remove();
                        }, 3000);
                    } else {
                        throw new Error('Failed to add to cart');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const notification = document.createElement('div');
                    notification.className = 'notification error';
                    notification.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error adding to cart';
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                });
            });
        });

        // Function to update cart popup
        function updateCartPopup() {
            fetch('get_cart_items.php')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.count;
                        cartCount.classList.add('bounce');
                        setTimeout(() => cartCount.classList.remove('bounce'), 300);
                    }

                    // Update cart popup if it's open
                    const cartPopup = document.querySelector('.cart-popup');
                    if (cartPopup && cartPopup.classList.contains('active')) {
                        const cartItems = cartPopup.querySelector('.cart-popup-items');
                        const totalAmount = cartPopup.querySelector('.total-amount');
                        
                        cartItems.innerHTML = data.items.map(item => `
                            <div class="cart-popup-item">
                                <img src="${item.image_url}" alt="${item.name}">
                                <div class="cart-item-details">
                                    <h4>${item.name}</h4>
                                    <p class="cart-item-price">DT${item.price.toFixed(2)}</p>
                                    <p>Quantity: ${item.quantity}</p>
                                </div>
                            </div>
                        `).join('');
                        
                        totalAmount.textContent = `DT${data.total.toFixed(2)}`;
                    }
                });
        }
    </script>
</body>
</html>
