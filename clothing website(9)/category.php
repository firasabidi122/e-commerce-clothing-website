<?php
session_start();
require_once 'config/db_connect.php';

// Get category from URL
$category = isset($_GET['cat']) ? $_GET['cat'] : '';

// Validate category
$valid_categories = ['men', 'women', 'unisex'];
if (!in_array($category, $valid_categories)) {
    header('Location: index.php');
    exit();
}

// Fetch products for this category
$stmt = $conn->prepare("SELECT * FROM products WHERE category = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($category); ?> Collection - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/cart-popup.css">
    <script src="js/cart.js" defer></script>
</head>
<body>
    <!-- Back button instead of header -->
    <div class="back-to-shop">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Shop
        </a>
    </div>

    <main>
        <div class="category-page">
            <div class="category-header">
                <h1><?php echo ucfirst($category); ?> Collection</h1>
                <p class="results-count"><?php echo $result->num_rows; ?> products found</p>
            </div>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="product-grid">
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <div class="product-card">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="product-link">
                                <div class="product-image">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="product-price">DT<?php echo number_format($product['price'], 2); ?></p>
                                </div>
                            </a>
                            <button class="add-to-cart-btn" 
                                data-product-id="<?php echo $product['id']; ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-price="<?php echo $product['price']; ?>"
                                data-image="<?php echo htmlspecialchars($product['image_url']); ?>">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-box-open"></i>
                    <p>No products found in this category.</p>
                    <a href="index.php" class="btn btn-primary">Return to Shop</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div class="cart-popup" id="cartPopup">
        <div class="cart-header">
            <h3>Shopping Cart</h3>
            <button class="close-cart"><i class="fas fa-times"></i></button>
        </div>
        <div class="cart-items"></div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span class="total-amount">DT0.00</span>
            </div>
            <div class="cart-buttons">
                <a href="cart.php" class="view-cart-btn">View Cart</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="checkout.php" class="checkout-btn">Checkout</a>
                <?php else: ?>
                    <a href="login.php?redirect=cart" class="checkout-btn disabled">Login to Checkout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
    .back-to-shop {
        position: fixed;
        top: 2rem;
        left: 2rem;
        z-index: 100;
    }

    .back-btn {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem 1.5rem;
        background: white;
        color: var(--text-color);
        text-decoration: none;
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 1rem;
    }

    .back-btn i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .back-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateX(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .back-btn:hover i {
        transform: translateX(-3px);
    }

    .category-page {
        padding: 2rem 5%;
        max-width: 1400px;
        margin: 2rem auto 0;
    }

    .category-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .category-header h1 {
        font-size: 2.5rem;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .results-count {
        color: #666;
        font-size: 1.1rem;
    }

    .no-results {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .no-results i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 1rem;
    }

    .no-results p {
        color: #666;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        background: var(--primary-color);
        color: white;
        text-decoration: none;
        border-radius: 25px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background: var(--secondary-color);
    }

    @media (max-width: 768px) {
        .back-to-shop {
            top: 1rem;
            left: 1rem;
        }

        .back-btn {
            padding: 0.8rem 1.2rem;
            font-size: 0.9rem;
        }

        .category-page {
            margin-top: 4rem;
        }

        .category-header h1 {
            font-size: 2rem;
        }
    }

    @media (max-width: 480px) {
        .back-btn span {
            display: none;
        }

        .back-btn {
            padding: 1rem;
            border-radius: 50%;
        }

        .back-btn i {
            margin: 0;
        }
    }

    .add-to-cart-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .add-to-cart-btn:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
    }

    .add-to-cart-btn.added {
        background: #2ecc71;
    }

    .add-to-cart-btn.added::after {
        content: '✓';
        margin-left: 0.5rem;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Cart popup styles */
    .cart-popup {
        position: fixed;
        top: 80px;
        right: 20px;
        width: 400px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        z-index: 1000;
        display: none;
        transform: translateY(-10px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .cart-popup.active {
        transform: translateY(0);
        opacity: 1;
    }

    /* Add the rest of the cart popup styles from index.php */
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attach event listeners to add to cart buttons
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
                        
                        // Just update the cart without showing popup
                        await updateCartPopup();
                    } else {
                        alert(data.message || 'Error adding product to cart');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error adding product to cart');
                }
            });
        });

        // Cart popup functionality
        async function updateCartPopup() {
            try {
                const response = await fetch('cart.php?action=get_cart_items');
                const data = await response.json();
                
                const cartItemsContainer = document.querySelector('.cart-items');
                const totalAmount = document.querySelector('.total-amount');
                
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
                } else {
                    cartItemsContainer.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
                    totalAmount.textContent = 'DT0.00';
                }
            } catch (error) {
                console.error('Error updating cart:', error);
            }
        }

        function showCartPopup() {
            const cartPopup = document.getElementById('cartPopup');
            cartPopup.style.display = 'block';
            setTimeout(() => cartPopup.classList.add('active'), 10);
        }

        // Close cart popup
        document.querySelector('.close-cart').addEventListener('click', function() {
            const cartPopup = document.getElementById('cartPopup');
            cartPopup.classList.remove('active');
            setTimeout(() => cartPopup.style.display = 'none', 300);
        });

        // Handle quantity changes and remove items
        document.querySelector('.cart-items').addEventListener('click', async function(e) {
            const target = e.target.closest('button');
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
                        quantitySpan.textContent = newQuantity;
                        await updateCartPopup();
                    } else {
                        alert(data.message || 'Error updating quantity');
                    }
                } catch (error) {
                    console.error('Error:', error);
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
                    console.error('Error:', error);
                    alert('Error removing item');
                }
            }
        });
    });
    </script>
</body>
</html>
