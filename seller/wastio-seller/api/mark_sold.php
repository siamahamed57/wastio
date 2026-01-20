<?php
header('Content-Type: application/json');
session_start();
require_once '../../../config/db.php';

// Check if user is logged in and is Waste Seller
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$seller_id = $_SESSION['user_id'];
$waste_id = isset($data['waste_id']) ? intval($data['waste_id']) : 0;

if ($waste_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Waste ID is required']);
    exit;
}

// Verify ownership
$check_query = "SELECT waste_id FROM waste_items WHERE waste_id = ? AND seller_id = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $waste_id, $seller_id);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);
$waste = mysqli_fetch_assoc($result);
mysqli_stmt_close($check_stmt);

if (!$waste) {
    echo json_encode(['success' => false, 'message' => 'Waste item not found or unauthorized']);
    exit;
}

// Mark as sold
$query = "UPDATE waste_items SET status = 'Sold' WHERE waste_id = ? AND seller_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $waste_id, $seller_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Waste item marked as sold']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to mark as sold']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
