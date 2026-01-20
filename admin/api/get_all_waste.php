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

// Fetch all waste items with seller and category information
$query = "SELECT w.waste_id, w.title, w.description, w.price, w.status, w.image_path, w.created_at,
          c.category_name,
          u.full_name as seller_name, u.email as seller_email,
          (SELECT COUNT(*) FROM buy_requests WHERE waste_id = w.waste_id) as request_count
          FROM waste_items w
          JOIN waste_categories c ON w.category_id = c.category_id
          JOIN users u ON w.seller_id = u.user_id
          ORDER BY w.created_at DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    $waste_items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $waste_items[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $waste_items]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch waste items']);
}

mysqli_close($conn);
?>
