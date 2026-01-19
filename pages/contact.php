<?php 
$pageTitle = "Contact Us";
include '../includes/header.php';
require_once '../config/db.php';

$msg = "";
$msgType = "";

if (isset($_POST['submit_contact'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $msg = "All fields are required!";
        $msgType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address!";
        $msgType = "error";
    } else {
        // In a real application, you would:
        // 1. Store in database
        // 2. Send email notification to admin
        // For now, we'll just show success message
        $msg = "Thank you for contacting us! We'll get back to you soon.";
        $msgType = "success";
    }
}
?>

<style>
    .contact-hero {
        background: linear-gradient(135deg, #005461 0%, #018790 100%);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .contact-hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .contact-hero p {
        font-size: 1.25rem;
        opacity: 0.9;
    }
    
    .contact-container {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
    }
    
    .contact-form-section {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .contact-form-section h2 {
        color: #005461;
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        color: #005461;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #00B7B5;
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 150px;
    }
    
    .submit-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #00B7B5, #018790);
        color: white;
        border: none;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
    }
    
    .contact-info-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .info-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        gap: 1.5rem;
        align-items: start;
    }
    
    .info-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #00B7B5, #018790);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .info-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .info-content h3 {
        color: #005461;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }
    
    .info-content p {
        color: #666;
        line-height: 1.6;
    }
    
    .info-content a {
        color: #00B7B5;
        text-decoration: none;
    }
    
    .info-content a:hover {
        text-decoration: underline;
    }
    
    .message {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }
    
    .message.success {
        background: #d1fae5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    
    .message.error {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    
    .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .social-links a {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #00B7B5, #018790);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: transform 0.3s ease;
    }
    
    .social-links a:hover {
        transform: translateY(-3px);
    }
    
    @media (max-width: 768px) {
        .contact-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="contact-hero">
    <h1>Contact Us</h1>
    <p>We'd love to hear from you. Get in touch with our team!</p>
</div>

<div class="contact-container">
    <div class="contact-form-section">
        <h2>Send us a Message</h2>
        
        <?php if ($msg): ?>
            <div class="message <?php echo $msgType; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            
            <button type="submit" name="submit_contact" class="submit-btn">
                <i class="fas fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>
    
    <div class="contact-info-section">
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="info-content">
                <h3>Our Location</h3>
                <p>123 Recycling Street<br>Green City, GC 12345<br>Bangladesh</p>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-phone"></i>
            </div>
            <div class="info-content">
                <h3>Phone</h3>
                <p>Main: <a href="tel:+8801234567890">+880 1234 567 890</a><br>
                Support: <a href="tel:+8801234567891">+880 1234 567 891</a></p>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="info-content">
                <h3>Email</h3>
                <p>General: <a href="mailto:info@wastio.com">info@wastio.com</a><br>
                Support: <a href="mailto:support@wastio.com">support@wastio.com</a></p>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="info-content">
                <h3>Business Hours</h3>
                <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                Saturday: 10:00 AM - 4:00 PM<br>
                Sunday: Closed</p>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-share-alt"></i>
            </div>
            <div class="info-content">
                <h3>Follow Us</h3>
                <p>Stay connected on social media</p>
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
