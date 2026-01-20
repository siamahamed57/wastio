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

// Fetch all categories
$query = "SELECT category_id, category_name FROM waste_categories ORDER BY category_name ASC";
$result = mysqli_query($conn, $query);

if ($result) {
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $categories]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch categories']);
}

mysqli_close($conn);
?>
