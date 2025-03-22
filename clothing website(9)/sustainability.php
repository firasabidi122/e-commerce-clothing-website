<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sustainability - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-to-home">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <main class="sustainability-page">
        <div class="sustainability-container">
            <div class="page-header">
                <div class="header-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h1>Our Commitment to Sustainability</h1>
                <p class="subtitle">Making fashion eco-friendly, one garment at a time</p>
            </div>

            <div class="sustainability-grid">
                <div class="eco-card">
                    <div class="card-icon">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h3>Sustainable Materials</h3>
                    <p>We use organic cotton, recycled polyester, and other eco-friendly materials that reduce environmental impact.</p>
                    <ul>
                        <li><i class="fas fa-seedling"></i> Organic Cotton</li>
                        <li><i class="fas fa-recycle"></i> Recycled Materials</li>
                        <li><i class="fas fa-leaf"></i> Natural Dyes</li>
                    </ul>
                </div>

                <div class="eco-card">
                    <div class="card-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3>Ethical Production</h3>
                    <p>Our manufacturing process prioritizes fair labor practices and minimizes environmental impact.</p>
                    <ul>
                        <li><i class="fas fa-hand-holding-heart"></i> Fair Wages</li>
                        <li><i class="fas fa-sun"></i> Solar Powered</li>
                        <li><i class="fas fa-water"></i> Water Conservation</li>
                    </ul>
                </div>

                <div class="eco-card">
                    <div class="card-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3>Eco Packaging</h3>
                    <p>All our packaging is made from recycled materials and is 100% biodegradable.</p>
                    <ul>
                        <li><i class="fas fa-box-open"></i> Recycled Boxes</li>
                        <li><i class="fas fa-seedling"></i> Biodegradable</li>
                        <li><i class="fas fa-minus-circle"></i> Plastic-Free</li>
                    </ul>
                </div>
            </div>

            <div class="impact-section">
                <h2>Our Environmental Impact</h2>
                <div class="impact-stats">
                    <div class="stat-card">
                        <div class="stat-number">80%</div>
                        <p>Less Water Usage</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">60%</div>
                        <p>Less CO2 Emissions</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">100%</div>
                        <p>Recyclable Packaging</p>
                    </div>
                </div>
            </div>

            <div class="commitment-section">
                <h2>Our Future Commitments</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="year">2024</div>
                        <div class="content">
                            <h4>100% Sustainable Materials</h4>
                            <p>Complete transition to fully sustainable and recycled materials</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="year">2025</div>
                        <div class="content">
                            <h4>Carbon Neutral</h4>
                            <p>Achieve carbon neutrality across all operations</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="year">2026</div>
                        <div class="content">
                            <h4>Zero Waste</h4>
                            <p>Implement zero-waste practices in all facilities</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .sustainability-page {
            min-height: 100vh;
            padding: 80px 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }

        .sustainability-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .page-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .header-icon {
            width: 100px;
            height: 100px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
        }

        .header-icon i {
            font-size: 3rem;
            color: white;
        }

        .page-header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .subtitle {
            color: #666;
            font-size: 1.2rem;
        }

        .sustainability-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .eco-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .eco-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .card-icon i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .eco-card h3 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        .eco-card p {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .eco-card ul {
            list-style: none;
            padding: 0;
        }

        .eco-card ul li {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
            color: #666;
        }

        .eco-card ul li i {
            color: var(--primary-color);
        }

        .impact-section {
            text-align: center;
            padding: 4rem 0;
            border-top: 1px solid #eee;
        }

        .impact-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .stat-card {
            padding: 2rem;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .commitment-section {
            padding-top: 4rem;
            border-top: 1px solid #eee;
        }

        .commitment-section h2 {
            text-align: center;
            margin-bottom: 3rem;
        }

        .timeline {
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline-item {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
            position: relative;
        }

        .year {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            min-width: 100px;
        }

        .content {
            flex: 1;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
            position: relative;
        }

        .content::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: var(--primary-color);
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .sustainability-container {
                padding: 2rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .timeline-item {
                flex-direction: column;
                gap: 1rem;
            }

            .content::before {
                left: 50%;
                top: -10px;
                transform: translateX(-50%);
            }
        }
    </style>
</body>
</html> 