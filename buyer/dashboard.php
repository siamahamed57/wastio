<<<<<<< Updated upstream
=======
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
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=5">
    <script src="/wastio/assets/js/agent_dashboard.js?v=5" defer></script>
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .stat-card {
            border: none;
            background: linear-gradient(145deg, var(--white) 0%, #f9ffff 100%);
            position: relative;
            z-index: 1;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: var(--primary-color);
            opacity: 0.05;
            border-radius: 50%;
            z-index: -1;
        }

        .stat-card.orange::after {
            background: #f39c12;
        }

        .stat-card.green::after {
            background: #27ae60;
        }

        .stat-header i {
            transition: transform 0.3s ease;
        }

        .stat-card:hover .stat-header i {
            transform: scale(1.2) rotate(10deg);
        }

        .action-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-top: 40px;
        }

        .action-card {
            background: var(--primary-gradient);
            color: white;
            padding: 30px;
            border-radius: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 84, 97, 0.2);
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 84, 97, 0.3);
        }

        .action-card h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .action-card p {
            margin: 5px 0 0;
            opacity: 0.8;
        }

        .action-card .icon {
            font-size: 3rem;
            opacity: 0.3;
        }

        [data-theme="dark"] .stat-card {
            background: linear-gradient(145deg, #242424 0%, #1a1a1a 100%);
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
                        👋 Welcome,
                        <?= htmlspecialchars($_SESSION['user_name']) ?>
                    </h2>
                    <p>Manage your recycled waste purchases efficiently.</p>
                </div>
                <div class="user-profile" style="display: flex; align-items: center;">
                    <button class="theme-btn" id="themeToggle" title="Switch to Dark Mode">🌙</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-header">
                        <span>Total Requests</span>
                        <i>📊</i>
                    </div>
                    <div class="stat-value">
                        <?= $total_req_count ?>
                    </div>
                    <div class="stat-label">Items you've requested</div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-header">
                        <span>Pending</span>
                        <i>⏳</i>
                    </div>
                    <div class="stat-value">
                        <?= $pending_req_count ?>
                    </div>
                    <div class="stat-label">Awaiting approval</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-header">
                        <span>Completed</span>
                        <i>✅</i>
                    </div>
                    <div class="stat-value">
                        <?= $completed_count ?>
                    </div>
                    <div class="stat-label">Successfully bought</div>
                </div>
            </div>

            <div class="action-cards">
                <a href="marketplace.php" class="action-card">
                    <div>
                        <h3>Explore Marketplace</h3>
                        <p>Browse new waste items available for sale.</p>
                    </div>
                    <div class="icon">🛒</div>
                </a>
                <a href="requests.php" class="action-card"
                    style="background: linear-gradient(135deg, #018790 0%, #00B7B5 100%);">
                    <div>
                        <h3>Track Requests</h3>
                        <p>View the status of your current orders.</p>
                    </div>
                    <div class="icon">📝</div>
                </a>
            </div>

            <div class="recent-section" style="margin-top: 40px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3>Recent Requests</h3>
                    <a href="requests.php"
                        style="color: var(--primary-color); font-weight: 600; text-decoration: none;">View All →</a>
                </div>
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
                                        <td>#<?= $row['request_id'] ?></td>
                                        <td><?= htmlspecialchars($row['title']) ?></td>
                                        <td>$<?= number_format($row['price'], 2) ?></td>
                                        <td><span
                                                class="status-badge <?= strtolower($row['status']) == 'pending' ? 'pending' : (strtolower($row['status']) == 'accepted' ? 'completed' : 'issue') ?>">
                                                <?= $row['status'] ?>
                                            </span></td>
                                        <td>
                                            <a href="requests.php" class="action-btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div style="text-align:center; padding: 40px; color: #666;">
                            <div style="font-size: 3rem; margin-bottom: 15px;">🔍</div>
                            <p>No requests found. Start exploring the marketplace!</p>
                            <a href="marketplace.php" class="action-btn btn-primary" style="margin-top: 15px;">Go to
                                Marketplace</a>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </main>
    </div>

</body>

</html>
>>>>>>> Stashed changes
