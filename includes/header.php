<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
$userRole = $isLoggedIn ? $_SESSION['role'] : '';

// Determine dashboard URL based on role
$dashboardUrl = '#';
if ($isLoggedIn) {
    switch ($userRole) {
        case 'Waste Buyer':
            $dashboardUrl = '/wastio/buyer/dashboard.php';
            break;
        case 'Waste Seller':
            $dashboardUrl = '/wastio/seller/wastio-seller/dashboard.php';
            break;
        case 'Collection Agent':
            $dashboardUrl = '/wastio/agent/pickupagent/dashboard.php';
            break;
        case 'System Admin':
            $dashboardUrl = '/wastio/admin/dashboard.php';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Wastio' : 'Wastio - Recycling Platform'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/wastio/assets/css/header.css">
    <?php if (isset($additionalCSS)) echo $additionalCSS; ?>
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="/wastio/index.php">
                    <i class="fas fa-recycle"></i>
                    <span>Wastio</span>
                </a>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="/wastio/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="/wastio/pages/about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a></li>
                    <li><a href="/wastio/pages/browse.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'browse.php' ? 'active' : ''; ?>">Browse Items</a></li>
                    <li><a href="/wastio/pages/how-it-works.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'how-it-works.php' ? 'active' : ''; ?>">How It Works</a></li>
                    <li><a href="/wastio/pages/contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <?php if ($isLoggedIn): ?>
                    <div class="user-menu">
                        <span class="user-greeting">Hi, <?php echo htmlspecialchars($userName); ?></span>
                        <a href="<?php echo $dashboardUrl; ?>" class="btn btn-dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="/wastio/auth/logout.php" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                <?php else: ?>
                    <a href="/wastio/auth/login.php" class="btn btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                <?php endif; ?>
            </div>
            
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
    
    <div class="mobile-menu" id="mobileMenu">
        <ul>
            <li><a href="/wastio/index.php">Home</a></li>
            <li><a href="/wastio/pages/about.php">About</a></li>
            <li><a href="/wastio/pages/browse.php">Browse Items</a></li>
            <li><a href="/wastio/pages/how-it-works.php">How It Works</a></li>
            <li><a href="/wastio/pages/contact.php">Contact</a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="<?php echo $dashboardUrl; ?>">Dashboard</a></li>
                <li><a href="/wastio/auth/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/wastio/auth/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
    
    <!-- Cookie Consent Popup -->
    <div class="cookie-consent" id="cookieConsent">
        <div class="cookie-content">
            <div class="cookie-icon">
                <i class="fas fa-cookie-bite"></i>
            </div>
            <div class="cookie-text">
                <h4>Cookie Notice</h4>
                <p>This website uses cookies to improve your browsing experience. By continuing, you agree to our use of cookies.</p>
            </div>
            <div class="cookie-actions">
                <button class="cookie-btn accept" onclick="acceptCookies()">
                    <i class="fas fa-check"></i> Accept
                </button>
                <button class="cookie-btn decline" onclick="declineCookies()">
                    <i class="fas fa-times"></i> Decline
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('active');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });
        
        // Cookie Consent
        function checkCookieConsent() {
            const consent = getCookie('cookie_consent');
            if (!consent) {
                setTimeout(() => {
                    document.getElementById('cookieConsent').classList.add('show');
                }, 1000); // Show after 1 second
            }
        }
        
        function acceptCookies() {
            setCookie('cookie_consent', 'accepted', 365);
            document.getElementById('cookieConsent').classList.remove('show');
        }
        
        function declineCookies() {
            setCookie('cookie_consent', 'declined', 365);
            document.getElementById('cookieConsent').classList.remove('show');
        }
        
        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = "expires=" + date.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }
        
        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
        
        // Check cookie consent on page load
        window.addEventListener('load', checkCookieConsent);
    </script>