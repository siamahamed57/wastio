<?php
header('Content-Type: application/json');
session_start();
require_once '../../../config/db.php';

// Check if user is logged in and is Waste Seller
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$seller_id = $_SESSION['user_id'];

// Fetch statistics
$stats = [];

// Total waste items
$query1 = "SELECT COUNT(*) as total FROM waste_items WHERE seller_id = ?";
$stmt1 = mysqli_prepare($conn, $query1);
mysqli_stmt_bind_param($stmt1, "i", $seller_id);
mysqli_stmt_execute($stmt1);
$result1 = mysqli_stmt_get_result($stmt1);
$stats['total_items'] = mysqli_fetch_assoc($result1)['total'];
mysqli_stmt_close($stmt1);

// Available items
$query2 = "SELECT COUNT(*) as total FROM waste_items WHERE seller_id = ? AND status = 'Available'";
$stmt2 = mysqli_prepare($conn, $query2);
mysqli_stmt_bind_param($stmt2, "i", $seller_id);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$stats['available_items'] = mysqli_fetch_assoc($result2)['total'];
mysqli_stmt_close($stmt2);

// Sold items
$query3 = "SELECT COUNT(*) as total FROM waste_items WHERE seller_id = ? AND status = 'Sold'";
$stmt3 = mysqli_prepare($conn, $query3);
mysqli_stmt_bind_param($stmt3, "i", $seller_id);
mysqli_stmt_execute($stmt3);
$result3 = mysqli_stmt_get_result($stmt3);
$stats['sold_items'] = mysqli_fetch_assoc($result3)['total'];
mysqli_stmt_close($stmt3);

// Total requests
$query4 = "SELECT COUNT(*) as total FROM buy_requests br 
           JOIN waste_items w ON br.waste_id = w.waste_id 
           WHERE w.seller_id = ?";
$stmt4 = mysqli_prepare($conn, $query4);
mysqli_stmt_bind_param($stmt4, "i", $seller_id);
mysqli_stmt_execute($stmt4);
$result4 = mysqli_stmt_get_result($stmt4);
$stats['total_requests'] = mysqli_fetch_assoc($result4)['total'];
mysqli_stmt_close($stmt4);

// Pending requests
$query5 = "SELECT COUNT(*) as total FROM buy_requests br 
           JOIN waste_items w ON br.waste_id = w.waste_id 
           WHERE w.seller_id = ? AND br.status = 'Pending'";
$stmt5 = mysqli_prepare($conn, $query5);
mysqli_stmt_bind_param($stmt5, "i", $seller_id);
mysqli_stmt_execute($stmt5);
$result5 = mysqli_stmt_get_result($stmt5);
$stats['pending_requests'] = mysqli_fetch_assoc($result5)['total'];
mysqli_stmt_close($stmt5);

echo json_encode(['success' => true, 'data' => $stats]);

mysqli_close($conn);
?>
