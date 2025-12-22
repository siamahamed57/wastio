<?php
session_start();
require_once "../config/db.php";

if(isset($_SESSION['user_id']) && isset($_SESSION['role'])){
    $userRole = $_SESSION['role'];
    
    switch($userRole){
        case 'Waste Buyer':
            header("Location: buyer/dashboard.php");
            exit;
        case 'Waste Seller':
            header("Location: seller/dashboard.php");
            exit;
        case 'Collection Agent':
            header("Location: agent/dashboard.php");
            exit;
        case 'System Admin':
            header("Location: admin/dashboard.php");
            exit;
    }
}

$msg = "";

if(isset($_POST['register'])){
    $fullname = mysqli_real_escape_string($conn, $_POST['name']);
    $userEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $phoneNum = mysqli_real_escape_string($conn, $_POST['phone']);
    $selectedRole = $_POST['role'];
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $getRoleId = "SELECT role_id FROM roles WHERE role_name='$selectedRole'";
    $roleRes = mysqli_query($conn, $getRoleId);
    $roleData = mysqli_fetch_assoc($roleRes);
    $rid = $roleData['role_id'];

    $insertUser = "INSERT INTO users (role_id, full_name, email, phone, password_hash) 
                   VALUES ('$rid', '$fullname', '$userEmail', '$phoneNum', '$hashedPassword')";

    if(mysqli_query($conn, $insertUser)){
        $msg = "Registration successful. Please login.";
    }else{
        $msg = "Registration failed.";
    }
}

if(isset($_POST['login'])){
    $loginEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $loginPass = $_POST['password'];

    $query = "SELECT users.*, roles.role_name FROM users 
              JOIN roles ON users.role_id = roles.role_id 
              WHERE email='$loginEmail' AND is_blocked=0";
    
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) == 1){
        $userData = mysqli_fetch_assoc($res);

        if(password_verify($loginPass, $userData['password_hash'])){
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['role'] = $userData['role_name'];
            $_SESSION['user_name'] = $userData['full_name'];

            $userRole = $userData['role_name'];
            
            switch($userRole){
                case 'Waste Buyer':
                    header("Location: buyer/dashboard.php");
                    exit;
                case 'Waste Seller':
                    header("Location: seller/dashboard.php");
                    exit;
                case 'Collection Agent':
                    header("Location: agent/dashboard.php");
                    exit;
                case 'System Admin':
                    header("Location: admin/dashboard.php");
                    exit;
            }
        }else{
            $msg = "Incorrect password.";
        }
    }else{
        $msg = "User not found or blocked.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wastio - Login & Registration</title>
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>

<div class="container">

<div class="header">
    <h1>♻ Wastio</h1>
    <p>Recycling Platform</p>
</div>

<?php if($msg != ""){ ?>
<p class="message"><?php echo $msg; ?></p>
<?php } ?>

<div class="box">

<div class="form-container active" id="loginForm">
<h3>Login</h3>
<form method="POST">
    <div class="form-group">
        <input type="email" name="email" placeholder="Email Address" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
    </div>
    <button type="submit" name="login">Login</button>
</form>
<div class="toggle-text">
    Don't have an account? <a href="#" class="toggle-link" onclick="toggleForm(event)">Register here</a>
</div>
</div>

<div class="form-container" id="registrationForm">
<h3>Registration</h3>
<form method="POST">
    <div class="form-group">
        <input type="text" name="name" placeholder="Full Name" required>
    </div>
    <div class="form-group">
        <input type="email" name="email" placeholder="Email Address" required>
    </div>
    <div class="form-group">
        <input type="text" name="phone" placeholder="Phone Number" required>
    </div>
    <div class="form-group">
        <select name="role" required>
            <option value="">Select Role</option>
            <option>Waste Seller</option>
            <option>Waste Buyer</option>
            <option>Collection Agent</option>
        </select>
    </div>
    <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
    </div>
    <button type="submit" name="register">Register</button>
</form>
<div class="toggle-text">
    Already have an account? <a href="#" class="toggle-link" onclick="toggleForm(event)">Login here</a>
</div>
</div>

</div>

</div>

<script src="../assets/js/index.js"></script>

</body>
</html>
