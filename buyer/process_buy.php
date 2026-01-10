<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Buyer') {
    header("Location: /wastio/auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_buy'])) {
    $waste_id = mysqli_real_escape_string($conn, $_POST['waste_id']);
    $buyer_id = $_SESSION['user_id'];

    // Check if item is still available
    $check_sql = "SELECT status FROM waste_items WHERE waste_id = '$waste_id'";
    $check_res = mysqli_query($conn, $check_sql);
    $item = mysqli_fetch_assoc($check_res);

    if ($item && $item['status'] === 'Available') {
        // Insert into buy_requests
        $insert_sql = "INSERT INTO buy_requests (waste_id, buyer_id, status) VALUES ('$waste_id', '$buyer_id', 'Pending')";

        if (mysqli_query($conn, $insert_sql)) {
            // Update waste_item status to 'Requested'
            $update_sql = "UPDATE waste_items SET status = 'Requested' WHERE waste_id = '$waste_id'";
            mysqli_query($conn, $update_sql);

            $_SESSION['success'] = "Purchase request sent successfully!";
        } else {
            $_SESSION['error'] = "Failed to send request. Please try again.";
        }
    } else {
        $_SESSION['error'] = "This item is no longer available.";
    }

    header("Location: marketplace.php");
    exit;
} else {
    header("Location: marketplace.php");
    exit;
}
?>