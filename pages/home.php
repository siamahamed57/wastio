<?php
require_once "config/db.php";

$recentQuery = "SELECT * FROM waste_items ORDER BY created_at DESC LIMIT 6";
$recentItems = mysqli_query($conn, $recentQuery);

$statsQuery = "SELECT 
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM waste_items) as total_items,
    (SELECT COUNT(*) FROM collection_requests) as total_collections";
$statsResult = mysqli_query($conn, $statsQuery);
$stats = mysqli_fetch_assoc($statsResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wastio - Recycling Platform</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>

<section class="hero-banner">
    <div class="hero-overlay">
        <div class="container">
            <div class="hero-text">
                <span class="hero-badge">♻️ Sustainable Future</span>
                <h1>Transform Waste Into Wealth</h1>
                <p>Join the largest recycling marketplace. Connect with buyers and sellers across the country.</p>
                <div class="hero-actions">
                    <a href="auth/login.php" class="btn-main">Start Selling</a>
                    <a href="auth/login.php" class="btn-outline">Browse Items</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-bar">
    <div class="container">
        <div class="stats-wrapper">
            <div class="stat-item">
                <h3><?php echo number_format($stats['total_users']); ?>+</h3>
                <p>Active Users</p>
            </div>
            <div class="stat-item">
                <h3><?php echo number_format($stats['total_items']); ?>+</h3>
                <p>Items Listed</p>
            </div>
            <div class="stat-item">
                <h3><?php echo number_format($stats['total_collections']); ?>+</h3>
                <p>Collections Done</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Support Available</p>
            </div>
        </div>
    </div>
</section>

<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Browse by Category</h2>
            <p>Find the materials you need</p>
        </div>
        
        <div class="category-cards">
            <div class="cat-card">
                <img src="https://images.unsplash.com/photo-1591695651119-e5a8e0e2a0d5?w=400&h=300&fit=crop" alt="Plastic">
                <div class="cat-overlay">
                    <h3>Plastic</h3>
                    <span>View Items →</span>
                </div>
            </div>
            <div class="cat-card">
                <img src="https://images.unsplash.com/photo-1594322436404-5a0526db4d13?w=400&h=300&fit=crop" alt="Paper">
                <div class="cat-overlay">
                    <h3>Paper</h3>
                    <span>View Items →</span>
                </div>
            </div>
            <div class="cat-card">
                <img src="https://images.unsplash.com/photo-1610557892470-55d9e80c0bce?w=400&h=300&fit=crop" alt="Metal">
                <div class="cat-overlay">
                    <h3>Metal</h3>
                    <span>View Items →</span>
                </div>
            </div>
            <div class="cat-card">
                <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&h=300&fit=crop" alt="Glass">
                <div class="cat-overlay">
                    <h3>Glass</h3>
                    <span>View Items →</span>
                </div>
            </div>
            <div class="cat-card">
                <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=400&h=300&fit=crop" alt="Electronics">
                <div class="cat-overlay">
                    <h3>Electronics</h3>
                    <span>View Items →</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="how-section">
    <div class="container">
        <div class="section-header">
            <h2>How It Works</h2>
            <p>Get started in 4 simple steps</p>
        </div>
        
        <div class="process-steps">
            <div class="process-card">
                <div class="process-icon">
                    <span>1</span>
                </div>
                <h3>Create Account</h3>
                <p>Sign up as a seller, buyer, or collection agent</p>
            </div>
            <div class="process-card">
                <div class="process-icon">
                    <span>2</span>
                </div>
                <h3>List or Browse</h3>
                <p>Post your items or explore available materials</p>
            </div>
            <div class="process-card">
                <div class="process-icon">
                    <span>3</span>
                </div>
                <h3>Connect & Deal</h3>
                <p>Negotiate prices and finalize transactions</p>
            </div>
            <div class="process-card">
                <div class="process-icon">
                    <span>4</span>
                </div>
                <h3>Complete</h3>
                <p>Agents handle pickup and delivery</p>
            </div>
        </div>
    </div>
</section>

<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2>Recently Listed Items</h2>
            <p>Fresh opportunities from our community</p>
        </div>
        
        <div class="featured-grid">
            <?php if(mysqli_num_rows($recentItems) > 0): ?>
                <?php while($item = mysqli_fetch_assoc($recentItems)): ?>
                    <div class="product-card">
                        <div class="product-img">
                            <?php if($item['image_path']): ?>
                                <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['title']; ?>">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=400&h=300&fit=crop" alt="Waste Item">
                            <?php endif; ?>
                            <span class="status-badge"><?php echo $item['status']; ?></span>
                        </div>
                        <div class="product-details">
                            <h4><?php echo $item['title']; ?></h4>
                            <div class="product-price">৳<?php echo number_format($item['price'], 2); ?></div>
                            <a href="auth/login.php" class="view-btn">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No items listed yet. Be the first!</p>
                    <a href="auth/login.php" class="btn-main">List Your Item</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="roles-section">
    <div class="container">
        <div class="section-header">
            <h2>Join as</h2>
            <p>Choose your role and start today</p>
        </div>
        
        <div class="roles-grid">
            <div class="role-box">
                <div class="role-icon">�</div>
                <h3>Waste Seller</h3>
                <ul>
                    <li>List unlimited items</li>
                    <li>Set your own prices</li>
                    <li>Track your sales</li>
                    <li>Get paid securely</li>
                </ul>
                <a href="auth/login.php" class="role-btn">Join Now</a>
            </div>
            <div class="role-box featured">
                <div class="popular-tag">Most Popular</div>
                <div class="role-icon">🏢</div>
                <h3>Waste Buyer</h3>
                <ul>
                    <li>Browse all categories</li>
                    <li>Direct seller contact</li>
                    <li>Bulk purchase options</li>
                    <li>Quality assurance</li>
                </ul>
                <a href="auth/login.php" class="role-btn">Join Now</a>
            </div>
            <div class="role-box">
                <div class="role-icon">�</div>
                <h3>Collection Agent</h3>
                <ul>
                    <li>Flexible schedule</li>
                    <li>Earn per delivery</li>
                    <li>Route optimization</li>
                    <li>Weekly payouts</li>
                </ul>
                <a href="auth/login.php" class="role-btn">Join Now</a>
            </div>
        </div>
    </div>
</section>

<section class="benefits-section">
    <div class="container">
        <div class="benefits-content">
            <div class="benefits-text">
                <h2>Why Choose Wastio?</h2>
                <div class="benefit-list">
                    <div class="benefit-item">
                        <div class="benefit-icon">✓</div>
                        <div>
                            <h4>Verified Users</h4>
                            <p>All users are verified for safe transactions</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">✓</div>
                        <div>
                            <h4>Secure Payments</h4>
                            <p>Multiple payment options with buyer protection</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">✓</div>
                        <div>
                            <h4>Fast Delivery</h4>
                            <p>Network of collection agents for quick pickup</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">✓</div>
                        <div>
                            <h4>24/7 Support</h4>
                            <p>Customer support always ready to help</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="benefits-image">
                <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=600&h=500&fit=crop" alt="Recycling">
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Make a Difference?</h2>
            <p>Join thousands of users who are earning while helping the environment</p>
            <a href="auth/login.php" class="btn-cta">Get Started Free</a>
        </div>
    </div>
</section>

</body>
</html>
