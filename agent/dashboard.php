<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'Collection Agent'){
    header("Location: /wastio/auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard</title>
</head>
<body>

<?php include "../includes/header.php"; ?>

<main class="dashboard">
    <h1>Collection Agent Dashboard</h1>

    <div class="cards">
        <div class="card">Assigned Pickups</div>
        <div class="card">Today's Schedule</div>
        <div class="card">Collection History</div>
    </div>
</main>

</body>
</html>
