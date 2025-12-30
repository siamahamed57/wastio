<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Collection Agent') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$agent_id = $_SESSION['user_id'];

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
                    📜 Collection History
                </h2>
            </div>

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