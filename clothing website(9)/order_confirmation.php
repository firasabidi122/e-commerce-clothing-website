<?php
session_start();
require_once 'config/db_connect.php';

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}

$order_id = (int)$_GET['order_id'];
$stmt = $conn->prepare("
    SELECT o.*, u.name as customer_name 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="confirmation-page">
        <div class="confirmation-container">
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <h1>Thank You for Your Order!</h1>
                <p>Your order #<?php echo $order_id; ?> has been successfully placed.</p>
            </div>

            <div class="order-details">
                <h2>Order Details</h2>
                <div class="detail-row">
                    <span>Order Number:</span>
                    <span>#<?php echo $order_id; ?></span>
                </div>
                <div class="detail-row">
                    <span>Total Amount:</span>
                    <span>DT<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
                <div class="detail-row">
                    <span>Status:</span>
                    <span class="status-<?php echo strtolower($order['status']); ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>
            </div>

            <div class="confirmation-actions">
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                <a href="orders.php" class="btn btn-secondary">View Orders</a>
            </div>
        </div>
    </div>

    <style>
    .confirmation-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: var(--background-color);
    }

    .confirmation-container {
        background: white;
        padding: 3rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
        max-width: 600px;
        width: 100%;
    }

    .success-message i {
        font-size: 4rem;
        color: #4CAF50;
        margin-bottom: 1rem;
    }

    .success-message h1 {
        color: #333;
        margin-bottom: 0.5rem;
    }

    .success-message p {
        color: #666;
        margin-bottom: 2rem;
    }

    .order-details {
        margin: 2rem 0;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .confirmation-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #333;
        border: 2px solid #eee;
    }

    @media (max-width: 768px) {
        .confirmation-container {
            padding: 2rem;
        }

        .confirmation-actions {
            flex-direction: column;
        }
    }
    </style>
</body>
</html> 