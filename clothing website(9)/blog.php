<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="back-to-home">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <main class="blog-page">
        <div class="blog-container">
            <div class="page-header">
                <div class="header-icon">
                    <i class="fas fa-blog"></i>
                </div>
                <h1>Eco Fashion Blog</h1>
                <p class="subtitle">Stay updated with sustainable fashion trends and tips</p>
            </div>

            <div class="blog-grid">
                <article class="blog-card">
                    <div class="blog-image">
                        <img src="sust.png" alt="Sustainable Fashion">
                        
                    </div>
                    <div class="blog-content">
                        <div class="blog-category">Sustainability</div>
                        <h2>The Future of Sustainable Fashion</h2>
                        <p>Discover how eco-friendly materials and ethical production are shaping the future of fashion industry.</p>
                        <div class="blog-meta">
                            <span><i class="far fa-calendar"></i> June 15, 2024</span>
                            
                        </div>
                        
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-image">
                        <img src="ecomat.png" alt="Eco Materials">
                    </div>
                    <div class="blog-content">
                        <div class="blog-category">Materials</div>
                        <h2>Guide to Eco-Friendly Fabrics</h2>
                        <p>Learn about the most sustainable materials in fashion and their environmental impact.</p>
                        <div class="blog-meta">
                            <span><i class="far fa-calendar"></i> June 10, 2024</span>
                            
                        </div>
                        
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-image">
                        <img src="fashion.png" alt="Ethical Fashion">
                    </div>
                    <div class="blog-content">
                        <div class="blog-category">Ethics</div>
                        <h2>Ethical Fashion Production</h2>
                        <p>Understanding the importance of fair labor practices in the fashion industry.</p>
                        <div class="blog-meta">
                            <span><i class="far fa-calendar"></i> June 5, 2024</span>
                            
                        </div>
                       
                    </div>
                </article>
            </div>
        </div>
    </main>

    <style>
        .blog-page {
            min-height: 100vh;
            padding: 80px 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }

        .blog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 4rem;
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
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
        }

        .header-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .page-header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .blog-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .blog-image {
            height: 200px;
            overflow: hidden;
        }

        .blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .blog-card:hover .blog-image img {
            transform: scale(1.1);
        }

        .blog-content {
            padding: 1.5rem;
        }

        .blog-category {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: rgba(76, 175, 80, 0.1);
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .blog-content h2 {
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .blog-content p {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .blog-meta {
            display: flex;
            gap: 1.5rem;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .blog-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: gap 0.3s ease;
        }

        .read-more:hover {
            gap: 0.8rem;
        }

        @media (max-width: 768px) {
            .blog-grid {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .blog-container {
                padding: 1rem;
            }
        }
    </style>
</body>
</html> 