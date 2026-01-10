<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Buyer') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Stats for requests page
$pending_sql = "SELECT COUNT(*) as count FROM buy_requests WHERE buyer_id = '$buyer_id' AND status = 'Pending'";
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, $pending_sql))['count'];

$accepted_sql = "SELECT COUNT(*) as count FROM buy_requests WHERE buyer_id = '$buyer_id' AND status = 'Accepted'";
$accepted_count = mysqli_fetch_assoc(mysqli_query($conn, $accepted_sql))['count'];

// Fetch all requests
$query = "SELECT br.*, wi.title, wi.price, wi.image_path, wc.category_name, u.full_name as seller_name 
          FROM buy_requests br 
          JOIN waste_items wi ON br.waste_id = wi.waste_id 
          JOIN waste_categories wc ON wi.category_id = wc.category_id
          JOIN users u ON wi.seller_id = u.user_id
          WHERE br.buyer_id = '$buyer_id' 
          ORDER BY br.request_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=5">
    <script src="/wastio/assets/js/agent_dashboard.js?v=5" defer></script>
    <style>
        .requests-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .req-stat-mini {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .req-stat-mini i {
            width: 45px;
            height: 45px;
            background: #f0fbfb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .req-stat-info h4 {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }

        .req-stat-info p {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .request-item-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .request-item-card:hover {
            transform: scale(1.005);
            box-shadow: var(--shadow-md);
        }

        .req-img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            background: #eee;
        }

        .req-details {
            flex-grow: 1;
        }

        .req-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .req-meta {
            font-size: 0.85rem;
            color: #666;
            display: flex;
            gap: 15px;
        }

        .req-status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff8e1;
            color: #f39c12;
        }

        .status-accepted {
            background: #e8f5e9;
            color: #27ae60;
        }

        .status-rejected {
            background: #ffebee;
            color: #c0392b;
        }

        [data-theme="dark"] .req-stat-mini,
        [data-theme="dark"] .request-item-card {
            background: #242424;
            border-color: #333;
        }

        [data-theme="dark"] .req-stat-mini i {
            background: #333;
        }

        [data-theme="dark"] .req-title {
            color: #eee;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <h2><button class="mobile-toggle" id="mobileToggle" style="margin-right:10px;">☰</button>
                        <i>📝</i> My Purchase Requests
                    </h2>
                    <p>Track the status of your waste item requests.</p>
                </div>
                <div class="user-profile">
                    <button class="theme-btn" id="themeToggle">🌙</button>
                </div>
            </div>

            <div class="requests-stats">
                <div class="req-stat-mini">
                    <i>⏳</i>
                    <div class="req-stat-info">
                        <h4>Pending</h4>
                        <p>
                            <?= $pending_count ?>
                        </p>
                    </div>
                </div>
                <div class="req-stat-mini">
                    <i>✅</i>
                    <div class="req-stat-info">
                        <h4>Accepted</h4>
                        <p>
                            <?= $accepted_count ?>
                        </p>
                    </div>
                </div>
                <div class="req-stat-mini">
                    <i>📦</i>
                    <div class="req-stat-info">
                        <h4>Total</h4>
                        <p>
                            <?= mysqli_num_rows($result) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="requests-list">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="request-item-card">
                            <?php if ($row['image_path']): ?>
                                <img src="/wastio/<?= htmlspecialchars($row['image_path']) ?>" class="req-img">
                            <?php else: ?>
                                <div class="req-img"
                                    style="display:flex; align-items:center; justify-content:center; font-size:2rem;">📦</div>
                            <?php endif; ?>

                            <div class="req-details">
                                <div class="req-title">
                                    <?= htmlspecialchars($row['title']) ?>
                                </div>
                                <div class="req-meta">
                                    <span>💰 Price: $
                                        <?= number_format($row['price'], 2) ?>
                                    </span>
                                    <span>👤 Seller:
                                        <?= htmlspecialchars($row['seller_name']) ?>
                                    </span>
                                    <span>📅 Requested:
                                        <?= date('M d, Y', strtotime($row['request_date'])) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="req-status">
                                <span class="req-status-badge status-<?= strtolower($row['status']) ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state-card" style="text-align: center; padding: 50px; border-radius: 20px;">
                        <div style="font-size: 4rem; margin-bottom: 20px;">📜</div>
                        <h3>You haven't made any requests yet.</h3>
                        <p>Browse the marketplace to find items you need!</p>
                        <a href="marketplace.php" class="action-btn btn-primary" style="margin-top:20px;">Go to
                            Marketplace</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>