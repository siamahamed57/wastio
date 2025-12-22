<?php
if(!isset($_SESSION)){
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<header>
    <nav class="nav-container">
        <a href="home.php" class="logo">♻ Wastio</a>
        
        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        
        <ul class="nav-menu" id="navMenu">
            <li><a href="home.php">Home</a></li>
            <li><a href="browse.php">Browse Waste Items</a></li>
            <li><a href="how-it-works.php">How It Works</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            
            <?php if($isLoggedIn){ ?>
                <?php
                $dashboardLink = "";
                switch($userRole){
                    case 'Waste Buyer':
                        $dashboardLink = "buyer/dashboard.php";
                        break;
                    case 'Waste Seller':
                        $dashboardLink = "seller/dashboard.php";
                        break;
                    case 'Collection Agent':
                        $dashboardLink = "agent/dashboard.php";
                        break;
                    case 'System Admin':
                        $dashboardLink = "admin/dashboard.php";
                        break;
                }
                ?>
                <li><a href="<?php echo $dashboardLink; ?>" class="dashboard-btn">Dashboard</a></li>
            <?php }else{ ?>
                <li><a href="auth/login.php" class="login-btn">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>

<script>
function toggleMenu(){
    var menu = document.getElementById('navMenu');
    menu.classList.toggle('active');
}
</script>
