<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>About EcoFriendly Fashion</h3>
            <p>Your destination for sustainable and eco-friendly fashion. We're committed to making a positive impact on the environment while keeping you stylish.</p>
            <div class="social-icons">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest-p"></i></a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="about.php"><i class="fas fa-leaf"></i> About Us</a></li>
                <li><a href="shop.php"><i class="fas fa-tshirt"></i> Shop Collection</a></li>
                <li><a href="sustainability.php"><i class="fas fa-recycle"></i> Sustainability</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a></li>
                <li><a href="faq.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Customer Service</h3>
            <ul>
                <li><a href="shipping.php"><i class="fas fa-truck"></i> Shipping Info</a></li>
                <li><a href="returns.php"><i class="fas fa-undo"></i> Returns Policy</a></li>
                <li><a href="size-guide.php"><i class="fas fa-ruler"></i> Size Guide</a></li>
                <li><a href="track-order.php"><i class="fas fa-box"></i> Track Order</a></li>
                <li><a href="privacy.php"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Newsletter</h3>
            <p>Subscribe to get special offers and eco-friendly fashion tips.</p>
            <form class="newsletter-form" action="subscribe.php" method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit">
                    <i class="fas fa-paper-plane"></i>
                    <span>Subscribe</span>
                </button>
            </form>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="payment-methods">
            <i class="fab fa-cc-visa" aria-label="Visa"></i>
            <i class="fab fa-cc-mastercard" aria-label="Mastercard"></i>
            <i class="fab fa-cc-paypal" aria-label="PayPal"></i>
            <i class="fab fa-cc-apple-pay" aria-label="Apple Pay"></i>
        </div>
        <p>&copy; <?php echo date('Y'); ?> EcoFriendly Fashion. All rights reserved.</p>
    </div>
</footer>

<style>
.site-footer {
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 4rem 5% 2rem;
    margin-top: 4rem;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.footer-section h3 {
    color: #fff;
    margin-bottom: 1.5rem;
    font-size: 1.2rem;
}

.footer-section p {
    color: #bdc3c7;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 0.8rem;
}

.footer-section ul li a {
    color: #bdc3c7;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: #fff;
}

.social-icons {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.social-icons a {
    color: #bdc3c7;
    font-size: 1.5rem;
    transition: color 0.3s ease;
}

.social-icons a:hover {
    color: #fff;
}

.newsletter-form {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.newsletter-form input {
    flex: 1;
    padding: 0.8rem;
    border: none;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.newsletter-form input::placeholder {
    color: #bdc3c7;
}

.newsletter-form button {
    padding: 0.8rem 1.2rem;
    border: none;
    border-radius: 4px;
    background: var(--primary-color);
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.newsletter-form button:hover {
    background: var(--secondary-color);
}

.footer-bottom {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}

.payment-methods {
    margin-bottom: 1rem;
}

.payment-methods i {
    font-size: 2rem;
    margin: 0 0.5rem;
    color: #bdc3c7;
}

.footer-bottom p {
    color: #bdc3c7;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .social-icons {
        justify-content: center;
    }

    .newsletter-form {
        max-width: 400px;
        margin: 1rem auto;
    }
}
</style> 