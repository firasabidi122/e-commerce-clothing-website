<?php
session_start();
require_once 'config/db_connect.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Calculate total
$total = 0;
$items = [];

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    if (!empty($ids)) {
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "SELECT * FROM products WHERE id IN ($placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($product = $result->fetch_assoc()) {
            $quantity = (int)$_SESSION['cart'][$product['id']];
            // Ensure quantity doesn't exceed current stock
            $quantity = min($quantity, $product['stock']);
            $_SESSION['cart'][$product['id']] = $quantity; // Update session with valid quantity
            
            $price = (float)$product['price'];
            $subtotal = $price * $quantity;
            $total += $subtotal;
            
            $product['quantity'] = $quantity;
            $product['subtotal'] = $subtotal;
            $items[] = $product;
        }
    }
}

// Update session with final quantities
session_write_close();
session_start();

// PayPal configuration
$paypal_client_id = "AYSq3RDGsmBLJE-otTkBtM-jBRd1TCQwFf9RGfwddNXWz0uFU9ztymylOhRS"; // Replace with your PayPal client ID
$currency = "USD";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- PayPal JS SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypal_client_id; ?>&currency=<?php echo $currency; ?>"></script>
</head>
<body>
    <!-- Back button -->
    <div class="back-to-cart">
        <a href="cart.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Cart
        </a>
    </div>

    <main class="checkout-page">
        <div class="checkout-container">
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div class="order-items">
                    <?php foreach ($items as $item): ?>
                        <div class="order-item">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="item-price">
                                    <span class="price">DT<?php echo number_format($item['price'], 2); ?></span>
                                    <span class="quantity">× <?php echo $item['quantity']; ?></span>
                                    <span class="subtotal">DT<?php echo number_format($item['subtotal'], 2); ?></span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-total">
                    <div class="subtotal">
                        <span>Subtotal</span>
                        <span>DT<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="shipping">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="total">
                        <span>Total</span>
                        <span>DT<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>

            <div class="payment-section">
                <h2>Payment Method</h2>
                <div class="payment-options">
                    <div class="payment-option active">
                        
                    </div>
                </div>
                
                <div id="paypal-button-container"></div>
                
                <div class="secure-payment">
                    <i class="fas fa-lock"></i>
                    <p>Your payment information is secure</p>
                </div>

                <div class="loading-overlay" style="display: none;">
                    <div class="spinner"></div>
                    <p>Processing your payment...</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'blue',
                shape:  'rect',
                label:  'paypal'
            },
            
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        description: 'EcoFriendly Fashion Order',
                        amount: {
                            value: '<?php echo number_format($total, 2, '.', ''); ?>'
                        }
                    }]
                });
            },

            onApprove: function(data, actions) {
                // Show loading overlay
                document.querySelector('.loading-overlay').style.display = 'flex';
                
                return actions.order.capture().then(function(details) {
                    // Send order details to server
                    return fetch('process_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            orderID: data.orderID,
                            paymentDetails: details
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = 'order_confirmation.php?order_id=' + data.order_id;
                        } else {
                            alert('There was a problem processing your payment. Please try again.');
                            document.querySelector('.loading-overlay').style.display = 'none';
                        }
                    });
                });
            },

            onError: function(err) {
                console.error('PayPal Error:', err);
                alert('There was a problem with PayPal. Please try again later.');
                document.querySelector('.loading-overlay').style.display = 'none';
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>