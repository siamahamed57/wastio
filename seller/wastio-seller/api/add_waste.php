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
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

// Validation
if (empty($title) || $category_id <= 0 || $price <= 0) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Handle image upload
$image_path = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../../../uploads/waste_items/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP allowed']);
        exit;
    }
    
    // Generate unique filename
    $filename = uniqid('waste_') . '.' . $file_extension;
    $target_path = $upload_dir . $filename;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $image_path = '/wastio/uploads/waste_items/' . $filename;
    }
}

// Insert waste item
$query = "INSERT INTO waste_items (seller_id, category_id, title, description, price, image_path, status) 
          VALUES (?, ?, ?, ?, ?, ?, 'Available')";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iissds", $seller_id, $category_id, $title, $description, $price, $image_path);

if (mysqli_stmt_execute($stmt)) {
    $waste_id = mysqli_insert_id($conn);
    echo json_encode([
        'success' => true, 
        'message' => 'Waste item added successfully',
        'waste_id' => $waste_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add waste item']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
