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

if (!isset($data['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$user_id = intval($data['user_id']);

// Prevent admin from approving themselves
if ($user_id == $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Cannot modify your own account']);
    exit;
}

// Update user approval status
$query = "UPDATE users SET is_approved = TRUE WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'User approved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to approve user']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
