<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Collection Agent') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$agent_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $update_query = "UPDATE users SET full_name = '$new_name' WHERE user_id = '$agent_id'";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['user_name'] = $new_name;
        $success_msg = "Profile updated successfully!";
    } else {
        $error_msg = "Failed to update profile.";
    }
}

// Handle Password Change
if (isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $conf_pass = $_POST['confirm_password'];

    $check_query = "SELECT password_hash FROM users WHERE user_id = '$agent_id'";
    $check_res = mysqli_query($conn, $check_query);
    $user_data = mysqli_fetch_assoc($check_res);

    if (password_verify($old_pass, $user_data['password_hash'])) {
        if ($new_pass === $conf_pass) {
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $update_pass_query = "UPDATE users SET password_hash = '$new_hash' WHERE user_id = '$agent_id'";
            if (mysqli_query($conn, $update_pass_query)) {
                $success_msg = "Password changed successfully!";
            } else {
                $error_msg = "Failed to update password.";
            }
        } else {
            $error_msg = "New passwords do not match.";
        }
    } else {
        $error_msg = "Incorrect current password.";
    }
}

$user_sql = "SELECT * FROM users WHERE user_id = '$agent_id'";
$user_res = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_res);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=4">
    <script src="/wastio/assets/js/agent_dashboard.js?v=4" defer></script>
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .profile-card {
            background: var(--white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            position: relative;
            transition: all 0.3s ease;
        }

        .profile-banner {
            height: 150px;
            background: var(--primary-gradient);
        }

        .profile-info-section {
            padding: 0 40px 40px;
            margin-top: -60px;
            text-align: center;
        }

        .avatar-large {
            width: 120px;
            height: 120px;
            background: #fff;
            border: 5px solid var(--white);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            box-shadow: var(--shadow-sm);
        }

        .profile-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .profile-email {
            color: var(--text-light);
            margin-bottom: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            text-align: left;
            margin-top: 20px;
        }

        .info-item {
            background: #f8fbfb;
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .info-item label {
            display: block;
            font-size: 0.8rem;
            color: #888;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .info-item span {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .form-section {
            display: none;
            text-align: left;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            animation: fadeIn 0.5s ease;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .edit-btn {
            margin-top: 30px;
            display: inline-block;
            padding: 12px 30px;
            background: var(--primary-color);
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .edit-btn.cancel {
            background: #6c757d;
        }

        .edit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        [data-theme="dark"] .profile-card {
            background: #242424;
        }

        [data-theme="dark"] .info-item,
        [data-theme="dark"] .form-control {
            background: #2a2a2a;
            border-color: #333;
            color: #eee;
        }

        [data-theme="dark"] .profile-name,
        [data-theme="dark"] .form-group label {
            color: #eee;
        }

        [data-theme="dark"] .form-section {
            border-color: #333;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <h2>
                        <button class="mobile-toggle" id="mobileToggle" style="margin-right:10px;">☰</button>
                        👤 My Profile
                    </h2>
                    <p>View and manage your agent account information.</p>
                </div>
                <button class="theme-btn" id="themeToggle" title="Switch to Dark Mode">🌙</button>
            </div>

            <div class="profile-container">
                <?php if ($success_msg): ?>
                    <div class="alert alert-success"><?= $success_msg ?></div>
                <?php endif; ?>
                <?php if ($error_msg): ?>
                    <div class="alert alert-error"><?= $error_msg ?></div>
                <?php endif; ?>

                <div class="profile-card">
                    <div class="profile-banner"></div>
                    <div class="profile-info-section">
                        <div class="avatar-large">👤</div>
                        <h1 class="profile-name"><?= htmlspecialchars($user['full_name']) ?></h1>
                        <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>

                        <div id="profileView">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Phone Number</label>
                                    <span><?= htmlspecialchars($user['phone'] ?? 'Not set') ?></span>
                                </div>
                                <div class="info-item">
                                    <label>User Role</label>
                                    <span><?= $_SESSION['role'] ?></span>
                                </div>
                                <div class="info-item" style="grid-column: 1 / -1;">
                                    <label>Account Created</label>
                                    <span><?= date('F d, Y', strtotime($user['created_at'])) ?></span>
                                </div>
                            </div>
                            <button class="edit-btn" onclick="toggleEdit()">Edit Profile Settings</button>
                        </div>

                        <div id="profileEdit" class="form-section">
                            <h2 style="margin-bottom: 20px; color: var(--primary-dark);">Update Information</h2>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" class="form-control"
                                        value="<?= htmlspecialchars($user['full_name']) ?>" required>
                                </div>
                                <button type="submit" name="update_profile" class="edit-btn">Save Changes</button>
                            </form>

                            <h2 style="margin-top: 40px; margin-bottom: 20px; color: var(--primary-dark);">Change
                                Password</h2>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="old_password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                <div class="btn-group">
                                    <button type="submit" name="change_password" class="edit-btn">Change
                                        Password</button>
                                    <button type="button" class="edit-btn cancel" onclick="toggleEdit()">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleEdit() {
            const view = document.getElementById('profileView');
            const edit = document.getElementById('profileEdit');
            if (view.style.display === 'none') {
                view.style.display = 'block';
                edit.style.display = 'none';
            } else {
                view.style.display = 'none';
                edit.style.display = 'block';
            }
        }
    </script>

</body>

</html>