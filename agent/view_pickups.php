<?php
require_once "../config/db.php";
session_start();

// ensure agent
if($_SESSION['role'] != 'agent'){
    header("Location: ../index.php");
    exit;
}

$sql = "SELECT * FROM collection_requests WHERE agent_id = '".$_SESSION['user_id']."'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Pickups</title>
  <link rel="stylesheet" href="../assets/css/agent.css">
</head>
<body>
<?php include "../includes/header.php"; ?>

<h2>Your Assigned Pickups</h2>
<table>
  <tr><th>ID</th><th>Status</th><th>Action</th></tr>
  <?php while($row = mysqli_fetch_assoc($result)){ ?>
  <tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['pickup_status']; ?></td>
    <td>
      <a href="pickup_details.php?id=<?php echo $row['id']; ?>">Details</a>
    </td>
  </tr>
  <?php } ?>
</table>

</body>
</html>