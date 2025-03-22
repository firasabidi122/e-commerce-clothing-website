<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Information - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-to-home">
        <a href="../index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <main class="service-page">
        <div class="service-container">
            <h1><i class="fas fa-shipping-fast"></i> Shipping Information</h1>
            
            <section class="service-section">
                <h2>Delivery Times</h2>
                <ul>
                    <li>Standard Delivery: 3-5 business days</li>
                    <li>Express Delivery: 1-2 business days</li>
                    <li>International Shipping: 7-14 business days</li>
                </ul>
            </section>

            <section class="service-section">
                <h2>Shipping Costs</h2>
                <ul>
                    <li>Free shipping on orders over DT200</li>
                    <li>Standard Delivery: DT10</li>
                    <li>Express Delivery: DT25</li>
                    <li>International Shipping: Calculated at checkout</li>
                </ul>
            </section>

            <section class="service-section">
                <h2>Tracking Your Order</h2>
                <p>Once your order is shipped, you will receive a tracking number via email to monitor your delivery status.</p>
            </section>
        </div>
    </main>

    <style>
        .service-page {
            min-height: 100vh;
            padding: 80px 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }

        .service-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .service-container h1 {
            color: var(--primary-color);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 2rem;
        }

        .service-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #eee;
        }

        .service-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .service-section h2 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .service-section ul {
            list-style: none;
            padding: 0;
        }

        .service-section ul li {
            padding: 0.5rem 0;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .service-section ul li:before {
            content: "•";
            color: var(--primary-color);
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .back-to-home {
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
        }

        .back-btn:hover {
            transform: translateX(-5px);
            background: var(--primary-color);
            color: white;
        }
    </style>
</body>
</html> 