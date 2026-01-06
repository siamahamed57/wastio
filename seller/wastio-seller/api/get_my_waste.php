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

// Fetch seller's waste items
$query = "SELECT w.waste_id, w.title, w.description, w.price, w.status, w.image_path, w.created_at,
          c.category_name, c.category_id,
          (SELECT COUNT(*) FROM buy_requests WHERE waste_id = w.waste_id) as request_count
          FROM waste_items w
          JOIN waste_categories c ON w.category_id = c.category_id
          WHERE w.seller_id = ?
          ORDER BY w.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $seller_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $waste_items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $waste_items[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $waste_items]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch waste items']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
