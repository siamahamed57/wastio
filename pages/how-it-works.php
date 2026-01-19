<?php 
$pageTitle = "How It Works";
include '../includes/header.php'; 
?>

<style>
    .how-hero {
        background: linear-gradient(135deg, #005461 0%, #018790 100%);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .how-hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .how-hero p {
        font-size: 1.25rem;
        max-width: 800px;
        margin: 0 auto;
        opacity: 0.9;
    }
    
    .how-container {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 0 2rem;
    }
    
    .steps-section {
        margin-bottom: 4rem;
    }
    
    .section-title {
        text-align: center;
        color: #005461;
        font-size: 2.5rem;
        margin-bottom: 3rem;
    }
    
    .steps-grid {
        display: grid;
        gap: 3rem;
    }
    
    .step-item {
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: 2rem;
        align-items: start;
    }
    
    .step-number {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #00B7B5, #018790);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 183, 181, 0.3);
    }
    
    .step-content {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .step-content h3 {
        color: #005461;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .step-content p {
        color: #666;
        line-height: 1.8;
        margin-bottom: 1rem;
    }
    
    .step-content ul {
        list-style: none;
        padding-left: 0;
    }
    
    .step-content li {
        padding: 0.5rem 0;
        color: #666;
        position: relative;
        padding-left: 1.5rem;
    }
    
    .step-content li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #00B7B5;
        font-weight: bold;
    }
    
    .roles-section {
        background: #f8f9fa;
        padding: 4rem 2rem;
        margin: 4rem 0;
    }
    
    .roles-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .roles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }
    
    .role-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .role-card:hover {
        transform: translateY(-10px);
    }
    
    .role-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #00B7B5, #018790);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .role-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .role-card h3 {
        color: #005461;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .role-card p {
        color: #666;
        line-height: 1.6;
    }
    
    .cta-section {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, #005461 0%, #018790 100%);
        color: white;
        border-radius: 16px;
        margin: 4rem 0;
    }
    
    .cta-section h2 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .cta-section p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    
    .cta-button {
        display: inline-block;
        padding: 1rem 2.5rem;
        background: white;
        color: #005461;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }
    
    .cta-button:hover {
        transform: translateY(-3px);
    }
</style>

<div class="how-hero">
    <h1>How Wastio Works</h1>
    <p>A simple, efficient platform connecting waste sellers with buyers for a sustainable future</p>
</div>

<div class="how-container">
    <div class="steps-section">
        <h2 class="section-title">Getting Started</h2>
        
        <div class="steps-grid">
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Create Your Account</h3>
                    <p>Sign up and choose your role:</p>
                    <ul>
                        <li>Waste Seller - List recyclable materials</li>
                        <li>Waste Buyer - Purchase waste items</li>
                        <li>Collection Agent - Facilitate pickups</li>
                    </ul>
                    <p>Your account will be reviewed and approved by our admin team within 24 hours.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Complete Your Profile</h3>
                    <p>Add your details to build trust:</p>
                    <ul>
                        <li>Contact information</li>
                        <li>Location details</li>
                        <li>Business information (if applicable)</li>
                    </ul>
                    <p>A complete profile helps you connect better with other users.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Start Trading</h3>
                    <p>Begin your recycling journey:</p>
                    <ul>
                        <li>Sellers: List your waste items with photos and prices</li>
                        <li>Buyers: Browse available items and send requests</li>
                        <li>Agents: Coordinate pickups and deliveries</li>
                    </ul>
                    <p>Our platform makes it easy to manage all your transactions.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>Complete Transactions</h3>
                    <p>Finalize your deals safely:</p>
                    <ul>
                        <li>Communicate directly with buyers/sellers</li>
                        <li>Arrange pickup or delivery</li>
                        <li>Confirm transaction completion</li>
                        <li>Rate your experience</li>
                    </ul>
                    <p>Track all your activities through your personalized dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="roles-section">
    <div class="roles-container">
        <h2 class="section-title">User Roles Explained</h2>
        
        <div class="roles-grid">
            <div class="role-card">
                <div class="role-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h3>Waste Seller</h3>
                <p>List recyclable materials, set prices, manage inventory, and connect with buyers. Perfect for businesses or individuals with recyclable waste.</p>
            </div>
            
            <div class="role-card">
                <div class="role-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Waste Buyer</h3>
                <p>Browse available items, send purchase requests, and acquire recyclable materials for your business or recycling needs.</p>
            </div>
            
            <div class="role-card">
                <div class="role-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Collection Agent</h3>
                <p>Facilitate the pickup and delivery of waste items, earning fees for your logistics services.</p>
            </div>
            
            <div class="role-card">
                <div class="role-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>System Admin</h3>
                <p>Manage platform operations, approve users, monitor transactions, and ensure smooth platform functioning.</p>
            </div>
        </div>
    </div>
</div>

<div class="how-container">
    <div class="cta-section">
        <h2>Ready to Get Started?</h2>
        <p>Join thousands of users making a difference in waste management</p>
        <a href="/wastio/auth/login.php" class="cta-button">
            <i class="fas fa-user-plus"></i> Create Account Now
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
