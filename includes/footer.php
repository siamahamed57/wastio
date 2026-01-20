    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- About Section -->
                <div class="footer-column">
                    <div class="footer-logo">
                        <i class="fas fa-recycle"></i>
                        <span>Wastio</span>
                    </div>
                    <p class="footer-description">
                        Connecting waste sellers with buyers for a sustainable future. Join us in making recycling easier and more accessible.
                    </p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="/wastio/index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="/wastio/pages/about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="/wastio/pages/browse.php"><i class="fas fa-chevron-right"></i> Browse Items</a></li>
                        <li><a href="/wastio/pages/how-it-works.php"><i class="fas fa-chevron-right"></i> How It Works</a></li>
                        <li><a href="/wastio/pages/contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>

                <!-- User Roles -->
                <div class="footer-column">
                    <h4>For Users</h4>
                    <ul class="footer-links">
                        <li><a href="/wastio/auth/login.php"><i class="fas fa-chevron-right"></i> Login</a></li>
                        <li><a href="/wastio/auth/login.php"><i class="fas fa-chevron-right"></i> Register</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="<?php echo $dashboardUrl ?? '#'; ?>"><i class="fas fa-chevron-right"></i> Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-column">
                    <h4>Contact Us</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Recycling Street<br>Green City, GC 12345</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span><a href="tel:+8801234567890">+880 1234 567 890</a></span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span><a href="mailto:info@wastio.com">info@wastio.com</a></span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Mon - Fri: 9:00 AM - 6:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Wastio. All rights reserved.</p>
                <p>Made with <i class="fas fa-heart"></i> for a sustainable future</p>
            </div>
        </div>
    </footer>

    <style>
        .main-footer {
            background: linear-gradient(135deg, #005461 0%, #018790 100%);
            color: #F4F4F4;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-column h4 {
            color: #00B7B5;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-logo i {
            font-size: 2rem;
            color: #00B7B5;
        }

        .footer-description {
            color: rgba(244, 244, 244, 0.8);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #F4F4F4;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #00B7B5;
            transform: translateY(-3px);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(244, 244, 244, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: #00B7B5;
        }

        .footer-links i {
            font-size: 0.75rem;
        }

        .footer-contact {
            list-style: none;
            padding: 0;
        }

        .footer-contact li {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: start;
        }

        .footer-contact i {
            color: #00B7B5;
            font-size: 1.25rem;
            margin-top: 0.25rem;
        }

        .footer-contact span {
            color: rgba(244, 244, 244, 0.8);
            line-height: 1.6;
        }

        .footer-contact a {
            color: rgba(244, 244, 244, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-contact a:hover {
            color: #00B7B5;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            text-align: center;
        }

        .footer-bottom p {
            color: rgba(244, 244, 244, 0.7);
            margin: 0.5rem 0;
        }

        .footer-bottom .fa-heart {
            color: #ff4444;
            animation: heartbeat 1.5s infinite;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .main-footer {
                padding: 2rem 0 1rem;
            }
        }
    </style>
</body>
</html>
