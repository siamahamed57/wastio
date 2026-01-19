<?php
header('Content-Type: application/json');
session_start();
require_once '../../config/db.php';

// Check if user is logged in and is System Admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'System Admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get total users count
$query1 = "SELECT COUNT(*) as total FROM users";
$result1 = mysqli_query($conn, $query1);
$total_users = mysqli_fetch_assoc($result1)['total'];

// Get pending approvals count
$query2 = "SELECT COUNT(*) as total FROM users WHERE is_approved = FALSE";
$result2 = mysqli_query($conn, $query2);
$pending_approvals = mysqli_fetch_assoc($result2)['total'];

// Get total waste items count
$query3 = "SELECT COUNT(*) as total FROM waste_items";
$result3 = mysqli_query($conn, $query3);
$total_waste_items = mysqli_fetch_assoc($result3)['total'];

// Get total buy requests count
$query4 = "SELECT COUNT(*) as total FROM buy_requests";
$result4 = mysqli_query($conn, $query4);
$total_requests = mysqli_fetch_assoc($result4)['total'];

// Get pending requests count
$query5 = "SELECT COUNT(*) as total FROM buy_requests WHERE status = 'Pending'";
$result5 = mysqli_query($conn, $query5);
$pending_requests = mysqli_fetch_assoc($result5)['total'];

// Get blocked users count
$query6 = "SELECT COUNT(*) as total FROM users WHERE is_blocked = TRUE";
$result6 = mysqli_query($conn, $query6);
$blocked_users = mysqli_fetch_assoc($result6)['total'];

$stats = [
    'total_users' => $total_users,
    'pending_approvals' => $pending_approvals,
    'total_waste_items' => $total_waste_items,
    'total_requests' => $total_requests,
    'pending_requests' => $pending_requests,
    'blocked_users' => $blocked_users
];

echo json_encode(['success' => true, 'data' => $stats]);

mysqli_close($conn);
?>
