<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Buyer') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Stats Queries
// 1. Total Requests
$total_req_sql = "SELECT COUNT(*) as count FROM buy_requests WHERE buyer_id = '$buyer_id'";
$total_req_res = mysqli_query($conn, $total_req_sql);
$total_req_count = mysqli_fetch_assoc($total_req_res)['count'];

// 2. Pending Requests
$pending_req_sql = "SELECT COUNT(*) as count FROM buy_requests WHERE buyer_id = '$buyer_id' AND status = 'Pending'";
$pending_req_res = mysqli_query($conn, $pending_req_sql);
$pending_req_count = mysqli_fetch_assoc($pending_req_res)['count'];

// 3. Completed (Sold/Accepted)
$completed_sql = "SELECT COUNT(*) as count FROM buy_requests WHERE buyer_id = '$buyer_id' AND status = 'Accepted'";
$completed_res = mysqli_query($conn, $completed_sql);
$completed_count = mysqli_fetch_assoc($completed_res)['count'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard - Wastio</title>
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
                        👋 Welcome,
                        <?= htmlspecialchars($_SESSION['user_name']) ?>
                    </h2>
                    <p>Find and purchase recycled waste items.</p>
                </div>
                <div class="user-profile" style="display: flex; align-items: center;">
                    <button class="theme-btn" id="themeToggle" title="Switch to Dark Mode">🌙</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-header">
                        <span>Total Requests</span>
                        <i>📝</i>
                    </div>
                    <div class="stat-value">
                        <?= $total_req_count ?>
                    </div>
                    <div class="stat-label">Overall items requested</div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-header">
                        <span>Pending</span>
                        <i>⏳</i>
                    </div>
                    <div class="stat-value">
                        <?= $pending_req_count ?>
                    </div>
                    <div class="stat-label">Awaiting seller response</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-header">
                        <span>Completed</span>
                        <i>✅</i>
                    </div>
                    <div class="stat-value">
                        <?= $completed_count ?>
                    </div>
                    <div class="stat-label">Successful acquisitions</div>
                </div>
            </div>

            <div class="recent-section">
                <h3>My Recent Requests</h3>
                <div class="table-container">
                    <?php
                    $recent_sql = "SELECT br.*, wi.title, wi.price FROM buy_requests br 
                                   JOIN waste_items wi ON br.waste_id = wi.waste_id 
                                   WHERE br.buyer_id = '$buyer_id' 
                                   ORDER BY br.request_date DESC LIMIT 5";
                    $recent_res = mysqli_query($conn, $recent_sql);

                    if (mysqli_num_rows($recent_res) > 0) {
                        ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($recent_res)): ?>
                                    <tr>
                                        <td>#
                                            <?= $row['request_id'] ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($row['title']) ?>
                                        </td>
                                        <td>$
                                            <?= number_format($row['price'], 2) ?>
                                        </td>
                                        <td><span
                                                class="status-badge <?= strtolower($row['status']) == 'pending' ? 'pending' : (strtolower($row['status']) == 'accepted' ? 'completed' : 'issue') ?>">
                                                <?= $row['status'] ?>
                                            </span></td>
                                        <td>
                                            <a href="request_details.php?id=<?= $row['request_id'] ?>"
                                                class="action-btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p style="text-align:center; padding: 20px; color: #666;">No requests found. Explore the
                            marketplace!</p>
                    <?php } ?>
                </div>
            </div>

        </main>
    </div>

</body>

</html>