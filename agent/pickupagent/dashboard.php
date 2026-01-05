<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Collection Agent') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$agent_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Stats Queries
// 1. Pending (Assigned)
$pending_sql = "SELECT COUNT(*) as count FROM collection_requests WHERE agent_id = '$agent_id' AND pickup_status = 'Assigned'";
$pending_res = mysqli_query($conn, $pending_sql);
$pending_count = mysqli_fetch_assoc($pending_res)['count'];

// 2. Today's
$today_sql = "SELECT COUNT(*) as count FROM collection_requests WHERE agent_id = '$agent_id' AND pickup_date = '$today' AND pickup_status = 'Assigned'";
$today_res = mysqli_query($conn, $today_sql);
$today_count = mysqli_fetch_assoc($today_res)['count'];

// 3. Completed
$history_sql = "SELECT COUNT(*) as count FROM collection_requests WHERE agent_id = '$agent_id' AND pickup_status = 'Completed'";
$history_res = mysqli_query($conn, $history_sql);
$history_count = mysqli_fetch_assoc($history_res)['count'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=2">
    <script src="/wastio/assets/js/agent_dashboard.js"></script>
</head>

<body>

    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <h2>
                        <button class="mobile-toggle" id="mobileToggle" style="margin-right:10px;">☰</button>
                        👋 Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>
                    </h2>
                    <p>Here's what's happening today.</p>
                </div>
                <div class="user-profile" style="display: flex; align-items: center;">
                    <div class="status-toggle">
                        <div class="status-indicator active"></div>
                        <span>Available</span>
                    </div>
                    <button class="theme-btn" id="themeToggle" title="Switch to Dark Mode">🌙</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card orange">
                    <div class="stat-header">
                        <span>Assigned Pickups</span>
                        <i>📦</i>
                    </div>
                    <div class="stat-value"><?= $pending_count ?></div>
                    <div class="stat-label">Need attention</div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-header">
                        <span>Today's Schedule</span>
                        <i>📅</i>
                    </div>
                    <div class="stat-value"><?= $today_count ?></div>
                    <div class="stat-label">Pickups for today</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-header">
                        <span>Total Completed</span>
                        <i>✅</i>
                    </div>
                    <div class="stat-value"><?= $history_count ?></div>
                    <div class="stat-label">All time collections</div>
                </div>
            </div>

            <div class="recent-section">
                <h3>Recent Assigned Activity</h3>
                <!-- Reusing table style -->
                <div class="table-container">
                    <?php
                    // Join to get extra info if needed, or just show basic info
                    $recent_sql = "SELECT * FROM collection_requests WHERE agent_id = '$agent_id' AND pickup_status = 'Assigned' ORDER BY pickup_date ASC LIMIT 5";
                    $recent_res = mysqli_query($conn, $recent_sql);

                    if (mysqli_num_rows($recent_res) > 0) {
                        ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Scheduled Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($recent_res)): ?>
                                    <tr>
                                        <td>#<?= $row['collection_id'] ?></td>
                                        <td><span class="status-badge pending"><?= $row['pickup_status'] ?></span></td>
                                        <td><?= date('M d, Y', strtotime($row['pickup_date'])) ?></td>
                                        <td>
                                            <a href="pickup_details.php?id=<?= $row['collection_id'] ?>"
                                                class="action-btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p style="text-align:center; padding: 20px; color: #666;">No assigned pickups found.</p>
                    <?php } ?>
                </div>
            </div>

        </main>
    </div>

</body>

</html>