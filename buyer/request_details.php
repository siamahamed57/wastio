<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Buyer') {
    header("Location: /wastio/auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: requests.php");
    exit;
}

$request_id = mysqli_real_escape_string($conn, $_GET['id']);
$buyer_id = $_SESSION['user_id'];

$query = "SELECT br.*, wi.title, wi.description, wi.price, wi.image_path, wc.category_name, u.full_name as seller_name, u.email as seller_email 
          FROM buy_requests br 
          JOIN waste_items wi ON br.waste_id = wi.waste_id 
          JOIN waste_categories wc ON wi.category_id = wc.category_id
          JOIN users u ON wi.seller_id = u.user_id
          WHERE br.request_id = '$request_id' AND br.buyer_id = '$buyer_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    header("Location: requests.php");
    exit;
}

$req = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=5">
    <script src="/wastio/assets/js/agent_dashboard.js?v=5" defer></script>
    <style>
        .details-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 0;
        }

        .details-img {
            width: 100%;
            height: 100%;
            min-height: 400px;
            object-fit: cover;
            background: #f0f0f0;
        }

        .details-content {
            padding: 40px;
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .details-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .details-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .details-desc {
            color: #555;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .info-section {
            margin-bottom: 25px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-label {
            color: #888;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 700;
            color: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .details-card {
                grid-template-columns: 1fr;
            }

            .details-img {
                min-height: 250px;
            }
        }

        [data-theme="dark"] .details-card {
            background: #242424;
        }

        [data-theme="dark"] .info-section {
            border-color: #333;
        }

        [data-theme="dark"] .details-title,
        [data-theme="dark"] .info-value {
            color: #eee;
        }

        [data-theme="dark"] .details-desc {
            color: #bbb;
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
                        <a href="requests.php" style="text-decoration:none; color:inherit;">⬅</a> Request #
                        <?= $req['request_id'] ?>
                    </h2>
                    <p>Detailed view of your purchase request.</p>
                </div>
                <div class="user-profile">
                    <button class="theme-btn" id="themeToggle">🌙</button>
                </div>
            </div>

            <div class="details-card">
                <div class="details-media">
                    <?php if ($req['image_path']): ?>
                        <img src="/wastio/<?= htmlspecialchars($req['image_path']) ?>" class="details-img">
                    <?php else: ?>
                        <div class="details-img"
                            style="display:flex; align-items:center; justify-content:center; font-size:5rem;">📦</div>
                    <?php endif; ?>
                </div>
                <div class="details-content">
                    <div class="details-header">
                        <h1 class="details-title">
                            <?= htmlspecialchars($req['title']) ?>
                        </h1>
                        <div class="details-price">$
                            <?= number_format($req['price'], 2) ?>
                        </div>
                    </div>

                    <p class="details-desc">
                        <?= nl2br(htmlspecialchars($req['description'])) ?>
                    </p>

                    <div class="info-section">
                        <div class="info-row">
                            <span class="info-label">Current Status</span>
                            <span
                                class="status-badge <?= strtolower($req['status']) == 'pending' ? 'pending' : (strtolower($req['status']) == 'accepted' ? 'completed' : 'issue') ?>">
                                <?= $req['status'] ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Category</span>
                            <span class="info-value">
                                <?= htmlspecialchars($req['category_name']) ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Seller Name</span>
                            <span class="info-value">
                                <?= htmlspecialchars($req['seller_name']) ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Seller Contact</span>
                            <span class="info-value">
                                <?= htmlspecialchars($req['seller_email']) ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Request Date</span>
                            <span class="info-value">
                                <?= date('F d, Y - h:i A', strtotime($req['request_date'])) ?>
                            </span>
                        </div>
                    </div>

                    <a href="requests.php" class="action-btn btn-primary" style="width: 100%; text-align: center;">Back
                        to My Requests</a>
                </div>
            </div>
        </main>
    </div>
</body>

</html>