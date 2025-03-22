<?php 
session_start();
require_once 'config/db_connect.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// If product not found, redirect to home
if (!$product) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-to-home">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <main class="product-details-page">
        <div class="product-details-container">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>

            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="product-price">DT<?php echo number_format($product['price'], 2); ?></div>
                
                <div class="product-description">
                    <h2>Description</h2>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="eco-benefits">
                    <h2>Eco-Friendly Benefits</h2>
                    <ul>
                        <li><i class="fas fa-leaf"></i> Made from sustainable materials</li>
                        <li><i class="fas fa-recycle"></i> Recyclable packaging</li>
                        <li><i class="fas fa-tint"></i> Water-saving production process</li>
                        <li><i class="fas fa-heart"></i> Ethically manufactured</li>
                    </ul>
                </div>

                <div class="product-actions">
                    <button class="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </main>

    <style>
        .product-details-page {
            min-height: 100vh;
            padding: 80px 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }

        .product-details-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .product-gallery {
            position: relative;
        }

        .main-image {
            width: 100%;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .main-image:hover img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 1rem;
        }

        .product-info h1 {
            color: #333;
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }

        .product-price {
            font-size: 1.8rem;
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .product-description {
            margin-bottom: 2rem;
        }

        .product-description h2 {
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }

        .product-description p {
            color: #666;
            line-height: 1.6;
        }

        .eco-benefits {
            margin-bottom: 2rem;
        }

        .eco-benefits h2 {
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }

        .eco-benefits ul {
            list-style: none;
            padding: 0;
        }

        .eco-benefits li {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.8rem;
            color: #666;
        }

        .eco-benefits li i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .product-actions {
            margin-top: 2rem;
        }

        .add-to-cart-btn {
            width: 100%;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        @media (max-width: 768px) {
            .product-details-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .main-image {
                height: 300px;
            }

            .product-info h1 {
                font-size: 1.8rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartBtn = document.querySelector('.add-to-cart-btn');
            
            addToCartBtn.addEventListener('click', async function() {
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
                        
                        // Show success message
                        alert('Product added to cart successfully!');
                    } else {
                        alert(data.message || 'Error adding product to cart');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error adding product to cart');
                }
            });
        });
    </script>
</body>
</html> 