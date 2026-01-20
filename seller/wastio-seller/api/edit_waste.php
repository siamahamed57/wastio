<?php
header('Content-Type: application/json');
session_start();
require_once '../../../config/db.php';

// Check if user is logged in and is Waste Seller
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$seller_id = $_SESSION['user_id'];
$waste_id = isset($_POST['waste_id']) ? intval($_POST['waste_id']) : 0;
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

// Validation
if ($waste_id <= 0 || empty($title) || $category_id <= 0 || $price <= 0) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Verify ownership
$check_query = "SELECT image_path FROM waste_items WHERE waste_id = ? AND seller_id = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $waste_id, $seller_id);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);
$existing = mysqli_fetch_assoc($result);
mysqli_stmt_close($check_stmt);

if (!$existing) {
    echo json_encode(['success' => false, 'message' => 'Waste item not found or unauthorized']);
    exit;
}

$image_path = $existing['image_path'];

// Handle new image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../../../uploads/waste_items/';
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit;
    }
    
    // Delete old image if exists
    if ($image_path && file_exists('../../../' . ltrim($image_path, '/'))) {
        unlink('../../../' . ltrim($image_path, '/'));
    }
    
    $filename = uniqid('waste_') . '.' . $file_extension;
    $target_path = $upload_dir . $filename;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $image_path = '/wastio/uploads/waste_items/' . $filename;
    }
}

// Update waste item
$query = "UPDATE waste_items SET category_id = ?, title = ?, description = ?, price = ?, image_path = ? 
          WHERE waste_id = ? AND seller_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "issdsii", $category_id, $title, $description, $price, $image_path, $waste_id, $seller_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Waste item updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update waste item']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
