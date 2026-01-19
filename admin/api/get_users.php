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

// Fetch all users with role information
$query = "SELECT u.user_id, u.full_name, u.email, u.phone, u.address, 
          u.is_approved, u.is_blocked, u.created_at,
          r.role_name, r.role_id
          FROM users u
          JOIN roles r ON u.role_id = r.role_id
          ORDER BY u.created_at DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $users]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch users']);
}

mysqli_close($conn);
?>
