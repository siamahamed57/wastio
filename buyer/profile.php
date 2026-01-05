<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Buyer') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$res = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Wastio Buyer</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=2">
    <script src="/wastio/assets/js/agent_dashboard.js"></script>
</head>

<body>

    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <h2><i>👤</i> My Profile</h2>
                </div>
                <button class="theme-btn" id="themeToggle">🌙</button>
            </div>

            <div class="profile-card"
                style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="profile-header" style="text-align: center; margin-bottom: 30px;">
                    <div class="avatar-large"
                        style="width: 100px; height: 100px; background: #eee; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 40px;">
                        👤
                    </div>
                    <h3>
                        <?= htmlspecialchars($user['full_name']) ?>
                    </h3>
                    <p style="color: #666;">
                        <?= htmlspecialchars($user['email']) ?>
                    </p>
                </div>

                <div class="profile-details" style="max-width: 500px; margin: 0 auto;">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #666; margin-bottom: 5px;">Phone</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 8px;">
                            <?= htmlspecialchars($user['phone'] ?? 'Not set') ?>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #666; margin-bottom: 5px;">Address</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 8px;">
                            <?= htmlspecialchars($user['address'] ?? 'Not set') ?>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #666; margin-bottom: 5px;">Role</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 8px;">
                            <?= $_SESSION['role'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>