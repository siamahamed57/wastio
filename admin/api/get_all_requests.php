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

// Fetch all buy requests with waste item, buyer, and seller information
$query = "SELECT br.request_id, br.status, br.request_date,
          w.waste_id, w.title as waste_title, w.price,
          buyer.user_id as buyer_id, buyer.full_name as buyer_name, buyer.email as buyer_email, buyer.phone as buyer_phone,
          seller.user_id as seller_id, seller.full_name as seller_name, seller.email as seller_email
          FROM buy_requests br
          JOIN waste_items w ON br.waste_id = w.waste_id
          JOIN users buyer ON br.buyer_id = buyer.user_id
          JOIN users seller ON w.seller_id = seller.user_id
          ORDER BY br.request_date DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    $requests = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $requests[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $requests]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch buy requests']);
}

mysqli_close($conn);
?>
