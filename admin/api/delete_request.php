<?php
header('Content-Type: application/json');
session_start();
require_once '../../config/db.php';

// Check if user is logged in and is System Admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'System Admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['request_id'])) {
    echo json_encode(['success' => false, 'message' => 'Request ID is required']);
    exit;
}

$request_id = intval($data['request_id']);

// Delete the buy request
$query = "DELETE FROM buy_requests WHERE request_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $request_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Buy request deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete buy request']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
