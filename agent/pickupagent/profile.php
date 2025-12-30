<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Collection Agent') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$agent_id = $_SESSION['user_id'];

// Mock Profile Update Logic - Assuming users table has name/phone/email
$user_sql = "SELECT * FROM users WHERE user_id = '$agent_id'";
$user_res = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_res);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle status update (simulated as field might not exist)
    $msg = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=2">
    <script src="/wastio/assets/js/agent_dashboard.js"></script>
    <style>
        .profile-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font- weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <h2>
                    <button class="mobile-toggle" id="mobileToggle" style="margin-right:10px;">☰</button>
                    👤 My Profile
                </h2>
            </div>

            <?php if (isset($msg))
                echo "<div class='alert' style='color:green'>$msg</div>"; ?>

            <div class="profile-card">
                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" value="<?= $user['full_name'] ?? 'Agent Name' ?>"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= $user['email'] ?? 'agent@wastio.com' ?>"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label>Current Status</label>
                        <select class="form-control">
                            <option>Available</option>
                            <option>Busy</option>
                            <option>Off Duty</option>
                        </select>
                    </div>
                    <button type="submit" class="action-btn btn-primary">Save Changes</button>
                </form>
            </div>
        </main>
    </div>

</body>

</html>