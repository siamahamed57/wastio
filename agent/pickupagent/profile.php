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

if (isset($_POST['change_details'])) {
    $fullName = mysqli_real_escape_string($conn, $_POST['full_name']);
    $newPassword = $_POST['new_password'];

    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET full_name = '$fullName', password_hash = '$hashedPassword' WHERE user_id = '$agent_id'";
    } else {
        $updateSql = "UPDATE users SET full_name = '$fullName' WHERE user_id = '$agent_id'";
    }

    if (mysqli_query($conn, $updateSql)) {
        $msg = "Profile updated successfully!";
        $_SESSION['user_name'] = $fullName; // Update session name
        // Refresh user data
        $user_res = mysqli_query($conn, $user_sql);
        $user = mysqli_fetch_assoc($user_res);
    } else {
        $msg = "Error updating profile: " . mysqli_error($conn);
    }
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
            font-weight: 600;
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
                echo "<div class='alert' style='color:green; margin-bottom: 20px;'>$msg</div>"; ?>

            <div class="profile-card">
                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                            value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email (Cannot be changed)</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                            readonly style="background-color: #f5f5f5; cursor: not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control"
                            placeholder="Leave blank to keep current password">
                    </div>
                    <button type="submit" name="change_details" class="action-btn btn-primary">Change Details</button>
                </form>
            </div>
        </main>
    </div>

</body>

</html>