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

if (!isset($data['waste_id'])) {
    echo json_encode(['success' => false, 'message' => 'Waste ID is required']);
    exit;
}

$waste_id = intval($data['waste_id']);

// Get image path before deletion for cleanup
$check_query = "SELECT image_path FROM waste_items WHERE waste_id = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, "i", $waste_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$waste = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$waste) {
    echo json_encode(['success' => false, 'message' => 'Waste item not found']);
    exit;
}

// Delete the waste item
$query = "DELETE FROM waste_items WHERE waste_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $waste_id);

if (mysqli_stmt_execute($stmt)) {
    // Delete image file if exists
    if ($waste['image_path'] && file_exists('../../' . ltrim($waste['image_path'], '/'))) {
        unlink('../../' . ltrim($waste['image_path'], '/'));
    }
    echo json_encode(['success' => true, 'message' => 'Waste item deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete waste item']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
