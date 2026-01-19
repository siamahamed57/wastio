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

if (!isset($data['user_id']) || !isset($data['block_status'])) {
    echo json_encode(['success' => false, 'message' => 'User ID and block status are required']);
    exit;
}

$user_id = intval($data['user_id']);
$block_status = $data['block_status'] ? 1 : 0;

// Prevent admin from blocking themselves
if ($user_id == $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Cannot block your own account']);
    exit;
}

// Update user block status
$query = "UPDATE users SET is_blocked = ? WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $block_status, $user_id);

if (mysqli_stmt_execute($stmt)) {
    $action = $block_status ? 'blocked' : 'unblocked';
    echo json_encode(['success' => true, 'message' => "User $action successfully"]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
