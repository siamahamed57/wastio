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
$request_id = isset($data['request_id']) ? intval($data['request_id']) : 0;

if ($request_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Request ID is required']);
    exit;
}

// Verify ownership
$check_query = "SELECT br.request_id FROM buy_requests br
                JOIN waste_items w ON br.waste_id = w.waste_id
                WHERE br.request_id = ? AND w.seller_id = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $request_id, $seller_id);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);
$request = mysqli_fetch_assoc($result);
mysqli_stmt_close($check_stmt);

if (!$request) {
    echo json_encode(['success' => false, 'message' => 'Request not found or unauthorized']);
    exit;
}

// Reject the request
$query = "UPDATE buy_requests SET status = 'Rejected' WHERE request_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $request_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Request rejected successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to reject request']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
