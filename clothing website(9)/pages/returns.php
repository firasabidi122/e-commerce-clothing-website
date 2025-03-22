<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns & Exchanges - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-to-home">
        <a href="../index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <main class="returns-page">
        <div class="returns-container">
            <div class="page-header">
                <div class="header-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h1>Returns & Exchanges</h1>
                <p class="subtitle">Easy, hassle-free returns within 30 days</p>
            </div>

            <div class="returns-grid">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3>Return Policy</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> 30-day return window</li>
                        <li><i class="fas fa-check"></i> Original condition with tags</li>
                        <li><i class="fas fa-check"></i> Unworn items only</li>
                        <li><i class="fas fa-check"></i> Free returns on defective items</li>
                    </ul>
                </div>

                <div class="info-card">
                    <div class="card-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3>Exchange Process</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Choose new size/color</li>
                        <li><i class="fas fa-check"></i> Free exchange shipping</li>
                        <li><i class="fas fa-check"></i> Quick processing</li>
                        
                    </ul>
                </div>

                <div class="info-card">
                    <div class="card-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3>Refund Information</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Original payment method</li>
                        <li><i class="fas fa-check"></i> 3-5 days processing</li>
                        
                    </ul>
                </div>
            </div>

            <div class="process-steps">
                <h2>How to Return or Exchange</h2>
                <div class="steps-container">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h4>Start Return</h4>
                        <p>Log into your account and select the items you wish to return</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h4>Package Items</h4>
                        <p>Pack items in original packaging with all tags attached</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h4>Ship Back</h4>
                        <p>Use our prepaid shipping label to send items back</p>
                    </div>
                  
                </div>
            </div>
        </div>
    </main>

    <style>
        .returns-page {
            min-height: 100vh;
            padding: 80px 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }

        .returns-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .header-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .page-header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        .returns-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .info-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .card-icon i {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .info-card h3 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .info-card ul {
            list-style: none;
            padding: 0;
        }

        .info-card ul li {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
            color: #666;
        }

        .info-card ul li i {
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .process-steps {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .process-steps h2 {
            color: #333;
            margin-bottom: 2rem;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .step {
            position: relative;
            padding: 1.5rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        .step h4 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .step p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .returns-container {
                padding: 2rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .returns-grid {
                grid-template-columns: 1fr;
            }

            .steps-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html> 