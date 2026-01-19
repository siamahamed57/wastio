<?php 
$pageTitle = "About Us";
include '../includes/header.php'; 
?>

<style>
    .about-hero {
        background: linear-gradient(135deg, #005461 0%, #018790 100%);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .about-hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .about-hero p {
        font-size: 1.25rem;
        max-width: 800px;
        margin: 0 auto;
        opacity: 0.9;
    }
    
    .about-content {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 0 2rem;
    }
    
    .mission-vision {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }
    
    .card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-10px);
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #00B7B5, #018790);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    
    .card-icon i {
        font-size: 1.75rem;
        color: white;
    }
    
    .card h3 {
        color: #005461;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .card p {
        color: #666;
        line-height: 1.8;
    }
    
    .values-section {
        background: #f8f9fa;
        padding: 4rem 2rem;
        margin: 4rem 0;
    }
    
    .values-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .values-container h2 {
        text-align: center;
        color: #005461;
        font-size: 2.5rem;
        margin-bottom: 3rem;
    }
    
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }
    
    .value-item {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .value-item i {
        font-size: 3rem;
        color: #00B7B5;
        margin-bottom: 1rem;
    }
    
    .value-item h4 {
        color: #005461;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }
    
    .value-item p {
        color: #666;
        font-size: 0.95rem;
    }
    
    .team-section {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 0 2rem;
    }
    
    .team-section h2 {
        text-align: center;
        color: #005461;
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .team-subtitle {
        text-align: center;
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 3rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }
    
    .stat-card {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #005461, #018790);
        border-radius: 12px;
        color: white;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 1rem;
        opacity: 0.9;
    }
</style>

<div class="about-hero">
    <h1>About Wastio</h1>
    <p>Transforming waste into opportunity through innovative recycling solutions and sustainable practices</p>
</div>

<div class="about-content">
    <div class="mission-vision">
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-bullseye"></i>
            </div>
            <h3>Our Mission</h3>
            <p>To create a sustainable future by connecting waste sellers with buyers, promoting recycling, and reducing environmental impact through an efficient digital platform.</p>
        </div>
        
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-eye"></i>
            </div>
            <h3>Our Vision</h3>
            <p>To become the leading recycling platform that empowers communities to participate in the circular economy and make a positive environmental impact.</p>
        </div>
        
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h3>Our Commitment</h3>
            <p>We are committed to providing a transparent, secure, and user-friendly platform that makes recycling accessible to everyone while supporting environmental sustainability.</p>
        </div>
    </div>
</div>

<div class="values-section">
    <div class="values-container">
        <h2>Our Core Values</h2>
        <div class="values-grid">
            <div class="value-item">
                <i class="fas fa-leaf"></i>
                <h4>Sustainability</h4>
                <p>Environmental responsibility in everything we do</p>
            </div>
            <div class="value-item">
                <i class="fas fa-shield-alt"></i>
                <h4>Integrity</h4>
                <p>Transparent and honest business practices</p>
            </div>
            <div class="value-item">
                <i class="fas fa-users"></i>
                <h4>Community</h4>
                <p>Building strong relationships and partnerships</p>
            </div>
            <div class="value-item">
                <i class="fas fa-lightbulb"></i>
                <h4>Innovation</h4>
                <p>Continuous improvement and creative solutions</p>
            </div>
        </div>
    </div>
</div>

<div class="team-section">
    <h2>Our Impact</h2>
    <p class="team-subtitle">Making a difference in waste management and recycling</p>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">1000+</div>
            <div class="stat-label">Active Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">5000+</div>
            <div class="stat-label">Items Recycled</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">50+</div>
            <div class="stat-label">Waste Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Platform Access</div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
