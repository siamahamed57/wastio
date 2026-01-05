<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Collection Agent') {
    header("Location: /wastio/auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: pickups.php");
    exit;
}

$collection_id = mysqli_real_escape_string($conn, $_GET['id']);
$agent_id = $_SESSION['user_id'];

// Detailed Fetch with JOINs
// Chain: collection_requests -> buy_requests -> waste_items -> users (Seller)
// Also get Waste Item Title
$sql = "SELECT cr.*, 
               wi.title as item_title, 
               u.full_name as seller_name, 
               u.phone as seller_phone, 
               u.address as seller_address,
               u.user_id as seller_id
        FROM collection_requests cr
        JOIN buy_requests br ON cr.request_id = br.request_id
        JOIN waste_items wi ON br.waste_id = wi.waste_id
        JOIN users u ON wi.seller_id = u.user_id
        WHERE cr.collection_id = '$collection_id' AND cr.agent_id = '$agent_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Pickup not found or access denied.");
}

$request = mysqli_fetch_assoc($result);

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    // Status must be one of enum: 'Assigned','Picked Up','Completed','Issue'
    $update_sql = "UPDATE collection_requests SET pickup_status = '$new_status' WHERE collection_id = '$collection_id'";
    if (mysqli_query($conn, $update_sql)) {
        $request['pickup_status'] = $new_status;
        $success_msg = "Status updated to " . $new_status;
    } else {
        $error_msg = "Failed to update status.";
    }
}

// Handle Confirmation (Sets to Completed)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_pickup'])) {
    $update_sql = "UPDATE collection_requests SET pickup_status = 'Completed' WHERE collection_id = '$collection_id'";
    if (mysqli_query($conn, $update_sql)) {
        header("Location: history.php?msg=completed");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Details - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=2">
    <script src="/wastio/assets/js/agent_dashboard.js"></script>

</head>

<body>

    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <h2>
                    <button class="mobile-toggle" id="mobileToggle" style="margin-right:10px;">☰</button>
                    📋 Pickup Details #<?= $request['collection_id'] ?>
                </h2>
                <div style="display:flex; align-items:center;">
                    <a href="pickups.php" class="action-btn btn-secondary">Back</a>
                    <button class="theme-btn" id="themeToggle" title="Switch to Dark Mode">🌙</button>
                </div>
            </div>

            <?php if (isset($success_msg))
                echo "<div style='color:green; margin-bottom:10px;'>$success_msg</div>"; ?>
            <?php if (isset($error_msg))
                echo "<div style='color:red; margin-bottom:10px;'>$error_msg</div>"; ?>

            <div class="details-grid">
                <div class="left-col">
                    <div class="info-card">
                        <h3>Seller Information</h3>
                        <div class="info-row">
                            <span class="label">Name</span>
                            <span><?= htmlspecialchars($request['seller_name']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Contact</span>
                            <span><?= htmlspecialchars($request['seller_phone']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Location</span>
                            <span><?= htmlspecialchars($request['seller_address']) ?></span>
                        </div>
                        <div class="map-frame">
                            <a href="https://maps.google.com/?q=<?= urlencode($request['seller_address']) ?>"
                                target="_blank">Open in Google Maps</a>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3>Item Details</h3>
                        <div class="info-row">
                            <span class="label">Item</span>
                            <span><?= htmlspecialchars($request['item_title']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Scheduled Date</span>
                            <span><?= $request['pickup_date'] ?? 'Not Set' ?></span>
                        </div>
                    </div>
                </div>

                <div class="right-col">
                    <div class="info-card">
                        <h3>Actions</h3>

                        <form method="POST" class="mb-20">
                            <label class="label">Update Status</label>
                            <select name="status" class="action-btn w-100 mt-5 mb-10 d-block">
                                <option value="Assigned" <?= $request['pickup_status'] == 'Assigned' ? 'selected' : '' ?>>
                                    Assigned (Pending)</option>
                                <option value="Picked Up" <?= $request['pickup_status'] == 'Picked Up' ? 'selected' : '' ?>>Picked Up</option>
                                <option value="Issue" <?= $request['pickup_status'] == 'Issue' ? 'selected' : '' ?>>
                                    Issue</option>
                            </select>
                            <button type="submit" name="update_status" class="action-btn btn-primary w-100">Update</button>
                        </form>

                        <?php if ($request['pickup_status'] != 'Completed'): ?>
                            <form method="POST" onsubmit="return confirm('Confirm completion of this pickup?');">
                                <button type="submit" name="confirm_pickup" class="action-btn btn-primary w-100"
                                    style="background-color: var(--success);">
                                    ✅ Confirm Pickup
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert-box alert-success">
                                Pickup Completed</div>
                        <?php endif; ?>

                        <br>
                        <br>
                        <button class="action-btn btn-danger w-100"
                            onclick="alert('Issue Reporting Modal would open here')">
                            ⚠️ Report Issue
                        </button>

                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>