<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-to-home">
        <a href="../index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <main class="faq-page">
        <div class="faq-container">
            <h1><i class="fas fa-question-circle"></i> Frequently Asked Questions</h1>
            
            <div class="faq-categories">
                <button class="faq-category active" data-category="general">
                    <i class="fas fa-info-circle"></i> General
                </button>
                <button class="faq-category" data-category="orders">
                    <i class="fas fa-shopping-bag"></i> Orders
                </button>
                <button class="faq-category" data-category="shipping">
                    <i class="fas fa-truck"></i> Shipping
                </button>
               
            </div>

            <div class="faq-list">
                <div class="faq-section" data-category="general">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What makes your clothing eco-friendly?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Our clothing is made from sustainable materials like organic cotton, recycled polyester, and bamboo fabric. We ensure ethical manufacturing practices and use eco-friendly packaging.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I create an account?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Click the 'Register' button in the top menu, fill in your details, and follow the verification process. It's quick and easy!</p>
                        </div>
                    </div>
                </div>

                <!-- Orders Section -->
                <div class="faq-section" data-category="orders" style="display: none;">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I place an order?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Browse our collection, select your items, add them to cart, and proceed to checkout. You'll need to create an account or log in to complete your purchase.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What payment methods do you accept?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We accept various payment methods including credit/debit cards, PayPal, and bank transfers. All payments are processed securely through our payment gateway.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How can I track my order?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Once your order ships, you'll receive a tracking number via email. You can also view your order status by logging into your account and visiting the "Order History" section.</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Section -->
                <div class="faq-section" data-category="shipping" style="display: none;">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What are your delivery times?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Standard delivery takes 3-5 business days. Express delivery is available for 1-2 business days.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Do you offer free shipping?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes! We offer free standard shipping on all orders.</p>
                        </div>
                    </div>

                   
                    </div>
                </div>

                <!-- Returns Section -->
                <div class="faq-section" data-category="returns" style="display: none;">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What is your return policy?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We offer a 30-day return window for all unworn items in their original packaging. Returns are free for defective items, and we provide a return shipping label for all returns.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How do I start a return?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Log into your account, go to "Order History", select the order and items you want to return, and follow the return process. Once approved, you'll receive a return shipping label via email.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>How long do refunds take?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Once we receive your return, we'll process it within 2-3 business days. Refunds typically appear in your account within 5-7 business days, depending on your bank.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Can I exchange an item?</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes! During the return process, you can choose to exchange your item for a different size or color. Exchanges are free and we'll ship your new item as soon as we receive your return.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .faq-page {
            min-height: 100vh;
            padding: 80px 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .faq-container h1 {
            color: var(--primary-color);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 2rem;
            text-align: center;
            justify-content: center;
        }

        .faq-categories {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .faq-category {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 30px;
            background: #f5f5f5;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .faq-category.active {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
        }

        .faq-category:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .faq-item {
            border: 1px solid #eee;
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .faq-question {
            padding: 1.2rem;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .faq-question h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #333;
            font-weight: 500;
        }

        .faq-question i {
            color: var(--primary-color);
            transition: transform 0.3s ease;
        }

        .faq-answer {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-answer p {
            padding: 1.2rem;
            margin: 0;
            color: #666;
            line-height: 1.6;
        }

        .faq-item.active .faq-question {
            background: var(--primary-color);
        }

        .faq-item.active .faq-question h3,
        .faq-item.active .faq-question i {
            color: white;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-item.active .faq-answer {
            max-height: 300px;
            border-top: 1px solid #eee;
        }

        @media (max-width: 768px) {
            .faq-container {
                padding: 1.5rem;
            }

            .faq-category {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }

            .faq-question h3 {
                font-size: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FAQ item toggle
            document.querySelectorAll('.faq-question').forEach(question => {
                question.addEventListener('click', () => {
                    const faqItem = question.parentElement;
                    faqItem.classList.toggle('active');
                });
            });

            // Category filtering
            document.querySelectorAll('.faq-category').forEach(category => {
                category.addEventListener('click', () => {
                    // Remove active class from all categories
                    document.querySelectorAll('.faq-category').forEach(cat => {
                        cat.classList.remove('active');
                    });

                    // Add active class to clicked category
                    category.classList.add('active');

                    // Show/hide relevant FAQ sections
                    const selectedCategory = category.dataset.category;
                    document.querySelectorAll('.faq-section').forEach(section => {
                        if (section.dataset.category === selectedCategory) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html> 