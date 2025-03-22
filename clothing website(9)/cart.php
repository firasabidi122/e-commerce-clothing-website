<?php
session_start();
require_once 'config/db_connect.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add this near the top of the file after session_start()
$is_logged_in = isset($_SESSION['user_id']);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['action'])) {
    $response = ['success' => false, 'message' => ''];
    
    // Get cart items for popup
    if (isset($_GET['action']) && $_GET['action'] === 'get_cart_items') {
        $cart_items = [];
        $total_items = 0;
        
        if (!empty($_SESSION['cart'])) {
            $ids = array_keys($_SESSION['cart']);
            if (!empty($ids)) {
                $placeholders = str_repeat('?,', count($ids) - 1) . '?';
                $sql = "SELECT * FROM products WHERE id IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    $quantity = (int)$_SESSION['cart'][$row['id']];
                    $total_items += $quantity;
                    
                    $cart_items[] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'price' => (float)$row['price'],
                        'image_url' => $row['image_url'],
                        'quantity' => $quantity
                    ];
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'items' => $cart_items,
            'total_items' => $total_items
        ]);
        exit;
    }
    
    // Add to cart
    if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = 1; // Force quantity to 1 for initial add
        
        // Verify product exists and has enough stock
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        
        if ($product) {
            if (!isset($_SESSION['cart'][$product_id])) {
                // If product is not in cart, add it with quantity 1
                $_SESSION['cart'][$product_id] = 1;
                $response = ['success' => true, 'message' => 'Product added to cart'];
            } else {
                // If product is already in cart, don't change the quantity
                $response = ['success' => true, 'message' => 'Product is already in cart'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Product not found'];
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Update quantity
    elseif (isset($_POST['update_quantity']) && isset($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        
        // Ensure quantity is at least 1
        if ($quantity < 1) {
            $response = ['success' => false, 'message' => 'Quantity must be at least 1'];
        } else {
            // Verify stock availability
            $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $product = $stmt->get_result()->fetch_assoc();
            
            if ($product && $quantity <= $product['stock']) {
                $_SESSION['cart'][$product_id] = $quantity;
                $response = [
                    'success' => true,
                    'message' => 'Quantity updated',
                    'quantity' => $quantity,
                    'product_id' => $product_id
                ];
            } else {
                $response = ['success' => false, 'message' => 'Not enough stock available'];
            }
        }
    }
    
    // Remove from cart
    elseif (isset($_POST['remove_from_cart']) && isset($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
        $response = ['success' => true, 'message' => 'Item removed from cart'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Get cart items with updated quantities
$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    if (!empty($ids)) {
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "SELECT * FROM products WHERE id IN ($placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $quantity = (int)$_SESSION['cart'][$row['id']];
            // Ensure quantity doesn't exceed current stock
            $quantity = min($quantity, $row['stock']);
            $_SESSION['cart'][$row['id']] = $quantity;
            
            $price = (float)$row['price'];
            $item_total = $price * $quantity;
            $total += $item_total;
            
            $row['quantity'] = $quantity;
            $cart_items[] = $row;
        }
    }
}

// Add this near the top of the file after session_start()
if (isset($_GET['get_cart'])) {
    $cart_items = [];
    $total = 0;
    
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($product = $result->fetch_assoc()) {
                $item = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image_url' => $product['image_url'],
                    'quantity' => $quantity
                ];
                $cart_items[] = $item;
                $total += $product['price'] * $quantity;
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'items' => $cart_items,
        'total' => $total
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-to-shop">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Shop
        </a>
    </div>

    <main class="cart-page">
        <div class="cart-container">
            <h1>Shopping Cart</h1>
            
            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="item-image">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="item-price">DT<?php echo number_format($item['price'], 2); ?></p>
                                <div class="item-controls">
                                    <form action="cart.php" method="POST" class="quantity-form">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <input type="hidden" name="update_quantity" value="1">
                                        <div class="quantity-controls">
                                            <button type="button" class="quantity-btn minus">-</button>
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                                   min="1" max="<?php echo $item['stock']; ?>" class="quantity-input" data-product-id="<?php echo $item['id']; ?>" readonly>
                                            <button type="button" class="quantity-btn plus">+</button>
                                        </div>
                                        <button type="submit" class="update-btn">
                                            Update
                                        </button>
                                    </form>
                                    <form action="cart.php" method="POST" class="remove-form">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <input type="hidden" name="remove_from_cart" value="1">
                                        <button type="submit" class="remove-btn">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>DT<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>DT<?php echo number_format($total, 2); ?></span>
                    </div>
                    <?php if ($is_logged_in): ?>
                        <a href="checkout.php" class="btn btn-checkout">Proceed to Checkout</a>
                    <?php else: ?>
                        <a href="login.php?redirect=cart" class="btn btn-login">
                            <i class="fas fa-user"></i> Login to Checkout
                        </a>
                    <?php endif; ?>
                    <a href="index.php" class="btn btn-continue">Continue Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Add this right after your opening <body> tag -->
    <div class="delete-popup" id="deletePopup">
        <div class="delete-popup-content">
            <div class="delete-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h3>Remove Item</h3>
            <p>Are you sure you want to remove this item from your cart?</p>
            <div class="delete-popup-buttons">
                <button class="cancel-delete">Cancel</button>
                <button class="confirm-delete">Remove</button>
            </div>
        </div>
    </div>

    <!-- Add these styles to your existing CSS -->
    <style>
    .delete-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1100;
        backdrop-filter: blur(4px);
    }

    .delete-popup.active {
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    .delete-popup-content {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        width: 90%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(20px);
        animation: slideUp 0.3s ease forwards;
    }

    .delete-icon {
        font-size: 3rem;
        color: #ff4444;
        margin-bottom: 1rem;
    }

    .delete-popup h3 {
        color: #333;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .delete-popup p {
        color: #666;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .delete-popup-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .delete-popup-buttons button {
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cancel-delete {
        background: #f5f5f5;
        color: #333;
    }

    .cancel-delete:hover {
        background: #eee;
        transform: translateY(-2px);
    }

    .confirm-delete {
        background: #ff4444;
        color: white;
    }

    .confirm-delete:hover {
        background: #ff3333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 68, 68, 0.2);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-login {
        background: #f5f5f5;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        border: 2px dashed #ddd;
    }

    .btn-login:hover {
        background: #eee;
        color: var(--primary-color);
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .btn-login i {
        font-size: 1.1rem;
    }

    .btn-checkout {
        background: var(--primary-color);
        color: white;
        padding: 1rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        display: block;
    }

    .btn-checkout:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
    }

    .btn-continue {
        background: #f5f5f5;
        color: #333;
        padding: 1rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
        display: block;
    }

    .btn-continue:hover {
        background: #eee;
        transform: translateY(-2px);
    }
    </style>

    <!-- Update the JavaScript for handling the delete popup -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deletePopup = document.getElementById('deletePopup');
        let productToDelete = null;

        // Function to show delete popup
        function showDeletePopup(productId) {
            productToDelete = productId;
            deletePopup.classList.add('active');
        }

        // Function to hide delete popup
        function hideDeletePopup() {
            deletePopup.classList.remove('active');
            productToDelete = null;
        }

        // Handle remove button clicks
        document.querySelector('.cart-items').addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-item');
            if (removeBtn) {
                e.preventDefault();
                const productId = removeBtn.dataset.productId;
                showDeletePopup(productId);
            }
        });

        // Handle cancel button
        document.querySelector('.cancel-delete').addEventListener('click', hideDeletePopup);

        // Handle confirm delete
        document.querySelector('.confirm-delete').addEventListener('click', async function() {
            if (productToDelete) {
                try {
                    const response = await fetch('cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `remove_from_cart=1&product_id=${productToDelete}`
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        // Remove the item from the DOM with animation
                        const itemToRemove = document.querySelector(`.cart-item[data-product-id="${productToDelete}"]`);
                        if (itemToRemove) {
                            itemToRemove.style.animation = 'slideOut 0.3s ease forwards';
                            setTimeout(() => {
                                itemToRemove.remove();
                                updateCartTotal();
                            }, 300);
                        }
                    } else {
                        alert(data.message || 'Error removing item');
                    }
                } catch (error) {
                    console.error('Error removing item:', error);
                    alert('Error removing item');
                }
                hideDeletePopup();
            }
        });

        // Close popup when clicking outside
        deletePopup.addEventListener('click', function(e) {
            if (e.target === deletePopup) {
                hideDeletePopup();
            }
        });

        // Close popup with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && deletePopup.classList.contains('active')) {
                hideDeletePopup();
            }
        });
    });
    </script>

    <script>
        // Quantity controls
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const productId = input.dataset.productId;
                const isIncrease = this.classList.contains('plus');
                let quantity = parseInt(input.value);

                if (isIncrease) {
                    quantity++;
                } else if (quantity > 1) {
                    quantity--;
                }

                // Update quantity in the database and session
                fetch('cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `update_quantity=1&product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        input.value = quantity;
                        updateCartTotal();
                        // Update the session immediately
                        sessionStorage.setItem(`cart_quantity_${productId}`, quantity);
                    } else {
                        alert(data.message);
                    }
                });
            });
        });

        // Direct quantity input
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.dataset.productId;
                let quantity = parseInt(this.value);
                
                // Ensure minimum quantity is 1
                if (quantity < 1) {
                    quantity = 1;
                    this.value = 1;
                }

                // Update quantity in the database and session
                fetch('cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `update_quantity=1&product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartTotal();
                        // Update the session immediately
                        sessionStorage.setItem(`cart_quantity_${productId}`, quantity);
                    } else {
                        alert(data.message);
                        // Reset to previous valid quantity
                        this.value = sessionStorage.getItem(`cart_quantity_${productId}`) || 1;
                    }
                });
            });
        });

        function updateCartTotal() {
            const cartSummary = document.querySelector('.cart-summary');
            const subtotal = cartSummary.querySelector('.summary-row span:nth-child(2)');
            const total = cartSummary.querySelector('.summary-row.total span:nth-child(2)');
            const cartItems = document.querySelectorAll('.cart-item');

            let newTotal = 0;

            cartItems.forEach(item => {
                const price = parseFloat(item.querySelector('.item-price').textContent.replace('DT', ''));
                const quantity = parseInt(item.querySelector('.quantity-input').value);
                newTotal += price * quantity;
            });

            subtotal.textContent = `DT${newTotal.toFixed(2)}`;
            total.textContent = `DT${newTotal.toFixed(2)}`;
        }
    </script>
</body>
</html>
