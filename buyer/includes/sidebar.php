<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar-overlay"></div>
<aside class="sidebar">
    <div class="sidebar-header">
        <a href="../../index.php" class="brand">♻ Wastio</a>
        <button class="mobile-toggle" id="mobileClose" style="display:none;">&times;</button>
    </div>
    <ul class="nav-links">
        <li>
            <a href="/wastio/buyer/dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
                <i>📊</i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/wastio/buyer/marketplace.php" class="<?= $currentPage == 'marketplace.php' ? 'active' : '' ?>">
                <i>🛒</i> <span>Marketplace</span>
            </a>
        </li>
        <li>
            <a href="/wastio/buyer/requests.php" class="<?= $currentPage == 'requests.php' ? 'active' : '' ?>">
                <i>📝</i> <span>My Requests</span>
            </a>
        </li>
        <li>
            <a href="/wastio/buyer/profile.php" class="<?= $currentPage == 'profile.php' ? 'active' : '' ?>">
                <i>👤</i> <span>Profile</span>
            </a>
        </li>
        <li style="margin-top: auto; border-top: 1px solid #eee;">
            <a href="/wastio/auth/logout.php" style="color: #dc3545;">
                <i>🚪</i> <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>