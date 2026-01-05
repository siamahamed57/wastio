<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Collection Agent') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$agent_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

// Fetch Today's
$today_sql = "SELECT cr.*, u.full_name as seller_name, u.address as seller_address 
              FROM collection_requests cr
              JOIN buy_requests br ON cr.request_id = br.request_id
              JOIN waste_items wi ON br.waste_id = wi.waste_id
              JOIN users u ON wi.seller_id = u.user_id
              WHERE cr.agent_id = '$agent_id' 
              AND cr.pickup_date = '$today' 
              AND cr.pickup_status = 'Assigned' 
              ORDER BY cr.pickup_date ASC";
$today_res = mysqli_query($conn, $today_sql);

// Fetch Upcoming
$upcoming_sql = "SELECT cr.*, u.full_name as seller_name 
                 FROM collection_requests cr
                 JOIN buy_requests br ON cr.request_id = br.request_id
                 JOIN waste_items wi ON br.waste_id = wi.waste_id
                 JOIN users u ON wi.seller_id = u.user_id
                 WHERE cr.agent_id = '$agent_id' 
                 AND cr.pickup_date > '$today' 
                 AND cr.pickup_status = 'Assigned' 
                 ORDER BY cr.pickup_date ASC";
$upcoming_res = mysqli_query($conn, $upcoming_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=2">
    <script src="/wastio/assets/js/agent_dashboard.js" defer></script>
</head>

<body>

    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <h2>
                    <button class="mobile-toggle" id="mobileToggle" style="margin-right:10px;">☰</button>
                    📅 Daily Schedule
                </h2>
                <button class="theme-btn" id="themeToggle" title="Switch to Dark Mode">🌙</button>
            </div>

            <div class="schedule-section">
                <h3>Today (<?= date('M d, Y') ?>)</h3>
                <div class="table-container">
                    <?php if (mysqli_num_rows($today_res) > 0) { ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Seller</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($today_res)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['seller_address']) ?></td>
                                        <td><?= htmlspecialchars($row['seller_name']) ?></td>
                                        <td><span class="status-badge pending"><?= $row['pickup_status'] ?></span></td>
                                        <td><a href="pickup_details.php?id=<?= $row['collection_id'] ?>"
                                                class="action-btn btn-primary">Go</a></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php } else {
                        echo "<p class='p-3'>No pickups scheduled for today.</p>";
                    } ?>
                </div>
            </div>

            <div class="schedule-section" style="margin-top:40px;">
                <h3>Upcoming</h3>
                <div class="table-container">
                    <?php if (mysqli_num_rows($upcoming_res) > 0) { ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Seller</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($upcoming_res)): ?>
                                    <tr>
                                        <td><?= date('M d', strtotime($row['pickup_date'])) ?></td>
                                        <td><?= htmlspecialchars($row['seller_name']) ?></td>
                                        <td><span class="status-badge pending"><?= $row['pickup_status'] ?></span></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php } else {
                        echo "<p class='p-3'>No upcoming pickups.</p>";
                    } ?>
                </div>
            </div>

        </main>
    </div>

</body>

</html>