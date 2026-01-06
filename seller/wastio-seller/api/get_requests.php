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

// Fetch buyer requests for seller's items
$query = "SELECT br.request_id, br.status, br.request_date,
          w.waste_id, w.title as waste_title, w.price,
          u.user_id as buyer_id, u.full_name as buyer_name, u.email as buyer_email, u.phone as buyer_phone
          FROM buy_requests br
          JOIN waste_items w ON br.waste_id = w.waste_id
          JOIN users u ON br.buyer_id = u.user_id
          WHERE w.seller_id = ?
          ORDER BY br.request_date DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $seller_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $requests = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $requests[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $requests]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch requests']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
