<?php
if(!isset($_SESSION)){
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<!-- CSS -->
<link rel="stylesheet" href="/wastio/assets/css/style.css">
<link rel="stylesheet" href="/wastio/assets/css/navbar.css">

<!-- JS -->
<script src="/wastio/assets/js/main.js" defer></script>

<header>
    <nav class="nav-container">
        <a href="/wastio/home.php" class="logo">♻ Wastio</a>

        <button class="menu-toggle" onclick="toggleMenu()">☰</button>

        <ul class="nav-menu" id="navMenu">
            <li><a href="/wastio/home.php">Home</a></li>
            <li><a href="/wastio/browse.php">Browse Waste Items</a></li>
            <li><a href="/wastio/how-it-works.php">How It Works</a></li>
            <li><a href="/wastio/about.php">About Us</a></li>
            <li><a href="/wastio/contact.php">Contact</a></li>

            <?php if($isLoggedIn){ 
                $dashboardLink = "";
                switch($userRole){
                    case 'Waste Buyer':
                        $dashboardLink = "/wastio/buyer/dashboard.php";
                        break;
                    case 'Waste Seller':
                        $dashboardLink = "/wastio/seller/dashboard.php";
                        break;
                    case 'Collection Agent':
                        $dashboardLink = "/wastio/agent/dashboard.php";
                        break;
                    case 'System Admin':
                        $dashboardLink = "/wastio/admin/dashboard.php";
                        break;
                }
            ?>
                <li><a href="<?= $dashboardLink ?>" class="dashboard-btn">Dashboard</a></li>
            <?php } else { ?>
                <li><a href="/wastio/auth/login.php" class="login-btn">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>

<script>
function toggleMenu(){
    document.getElementById('navMenu').classList.toggle('active');
}
</script>
