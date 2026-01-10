<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar-overlay"></div>
<aside class="sidebar">
    <div class="sidebar-header">
        <a href="/wastio/agent/pickupagent/dashboard.php" class="brand">♻ Wastio</a>
        <button class="mobile-toggle" id="mobileClose">&times;</button>
    </div>
    <ul class="nav-links">
        <li>
            <a href="/wastio/agent/pickupagent/dashboard.php"
                class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
                <i>📊</i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/wastio/agent/pickupagent/pickups.php"
                class="<?= $currentPage == 'pickups.php' ? 'active' : '' ?>">
                <i>🚛</i> <span>Assigned Pickups</span>
            </a>
        </li>
        <li>
            <a href="/wastio/agent/pickupagent/schedule.php"
                class="<?= $currentPage == 'schedule.php' ? 'active' : '' ?>">
                <i>📅</i> <span>Daily Schedule</span>
            </a>
        </li>
        <li>
            <a href="/wastio/agent/pickupagent/history.php"
                class="<?= $currentPage == 'history.php' ? 'active' : '' ?>">
                <i>📜</i> <span>History</span>
            </a>
        </li>
        <li>
            <a href="/wastio/agent/pickupagent/profile.php"
                class="<?= $currentPage == 'profile.php' ? 'active' : '' ?>">
                <i>👤</i> <span>Profile</span>
            </a>
        </li>
        <li style="margin-top: auto; border-top: 1px solid #eee;">
            <a href="/wastio/auth/logout.php" id="logoutLink" style="color: #dc3545;">
                <i>🚪</i> <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>