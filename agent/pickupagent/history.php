<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Collection Agent') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$agent_id = $_SESSION['user_id'];
$msg = "";

// Handle Clear History
if (isset($_POST['clear_history'])) {
    $clear_sql = "DELETE FROM collection_requests WHERE agent_id = '$agent_id' AND (pickup_status = 'Completed' OR pickup_status = 'Issue')";
    if (mysqli_query($conn, $clear_sql)) {
        $msg = "History cleared successfully.";
    } else {
        $msg = "Error clearing history: " . mysqli_error($conn);
    }
}

// Initial query for history
$sql = "SELECT cr.*, u.full_name as seller_name 
        FROM collection_requests cr
        JOIN buy_requests br ON cr.request_id = br.request_id
        JOIN waste_items wi ON br.waste_id = wi.waste_id
        JOIN users u ON wi.seller_id = u.user_id
        WHERE cr.agent_id = '$agent_id' 
        AND (cr.pickup_status = 'Completed' OR cr.pickup_status = 'Issue') 
        ORDER BY cr.pickup_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection History - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=4">
    <script src="/wastio/assets/js/agent_dashboard.js?v=4" defer></script>
</head>

<body>

    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <h2>
                        <button class="mobile-toggle" id="mobileToggle" style="margin-right:10px;">☰</button>
                        📜 Collection History
                    </h2>
                    <p>Track your past collections and reports.</p>
                </div>
                <div style="display:flex; align-items:center;">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <form method="POST"
                            onsubmit="return confirm('Are you sure you want to clear your collection history? This action cannot be undone.');">
                            <button type="submit" name="clear_history" class="action-btn btn-danger"
                                style="margin-right: 15px;">Clear History</button>
                        </form>
                    <?php endif; ?>
                    <button class="theme-btn" id="themeToggle" title="Switch to Dark Mode">🌙</button>
                </div>
            </div>

            <?php if ($msg): ?>
                <div class="alert alert-success" style="margin-bottom: 20px;"><?= $msg ?></div>
            <?php endif; ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pickup Date</th>
                            <th>Seller</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td>#<?= $row['collection_id'] ?></td>
                                    <td><?= isset($row['pickup_date']) ? date('M d, Y', strtotime($row['pickup_date'])) : '-' ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['seller_name']) ?></td>
                                    <td>
                                        <span
                                            class="status-badge <?= strtolower(str_replace(' ', '', $row['pickup_status'])) ?>">
                                            <?= $row['pickup_status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="pickup_details.php?id=<?= $row['collection_id'] ?>" class="action-btn">View</a>
                                    </td>
                                </tr>
                            <?php endwhile;
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>No history found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>