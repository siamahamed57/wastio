<?php
require_once "config/db.php";
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
<section class="hero">
    <div class="hero-content">
        <h1>Turn Your Waste Into Worth</h1>
        <p>Connect with buyers and sellers in the recycling ecosystem</p>
        <div class="hero-buttons">
            <a href="auth/login.php" class="btn-primary">Get Started</a>
            <a href="#how-it-works" class="btn-secondary">Learn More</a>
        </div>
    </div>
</section>

<section class="features">
    <div class="container-main">
        <h2>Why Choose Wastio?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="icon">♻️</div>
                <h3>Eco-Friendly</h3>
                <p>Contribute to a sustainable future by recycling waste materials</p>
            </div>
            <div class="feature-card">
                <div class="icon">💰</div>
                <h3>Earn Money</h3>
                <p>Sell your waste materials and turn trash into cash</p>
            </div>
            <div class="feature-card">
                <div class="icon">🚚</div>
                <h3>Easy Pickup</h3>
                <p>Collection agents handle the logistics for you</p>
            </div>
            <div class="feature-card">
                <div class="icon">🤝</div>
                <h3>Trusted Platform</h3>
                <p>Safe and secure transactions for all users</p>
            </div>
        </div>
    </div>
</section>

<section class="how-it-works" id="how-it-works">
    <div class="container-main">
        <h2>How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Register</h3>
                <p>Sign up as a seller, buyer, or collection agent</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>List or Browse</h3>
                <p>Post your waste items or browse available materials</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Connect</h3>
                <p>Buyers request items, sellers accept offers</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Complete</h3>
                <p>Collection agents handle pickup and delivery</p>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container-main">
        <h2>Ready to Start Recycling?</h2>
        <p>Join thousands of users making a difference</p>
        <a href="auth/login.php" class="btn-primary">Join Now</a>
    </div>
</section>



</body>
</html>
