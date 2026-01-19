<?php
session_start();
require_once "../config/db.php";

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $userRole = $_SESSION['role'];

    switch ($userRole) {
        case 'Waste Buyer':
            header("Location: /wastio/buyer/dashboard.php");
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

// Auto-login with Remember Me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me']) && isset($_COOKIE['remember_token'])) {
    $userId = intval($_COOKIE['remember_me']);
    
    $query = "SELECT u.user_id, u.full_name, u.email, u.is_approved, u.is_blocked, r.role_name 
              FROM users u 
              JOIN roles r ON u.role_id = r.role_id 
              WHERE u.user_id = $userId AND u.is_blocked = FALSE AND u.is_approved = TRUE";
    
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role_name'];
        
        // Redirect based on role
        switch ($user['role_name']) {
            case 'Waste Buyer':
                header("Location: /wastio/buyer/dashboard.php");
                exit;
            case 'Waste Seller':
                header("Location: /wastio/seller/wastio-seller/dashboard.php");
                exit;
            case 'Collection Agent':
                header("Location: /wastio/agent/pickupagent/dashboard.php");
                exit;
            case 'System Admin':
                header("Location: /wastio/admin/dashboard.php");
                exit;
        }
    } else {
        // Invalid cookie, clear it
        setcookie('remember_me', '', time() - 3600, '/');
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

$msg = "";

if (isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($conn, trim($_POST['name']));
    $userEmail = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phoneNum = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $selectedRole = $_POST['role'];
    $password = $_POST['password'];
    
    // Validation
    $errors = [];
    
    // Name validation
    if (empty($fullname) || strlen($fullname) < 3) {
        $errors[] = "Name must be at least 3 characters long";
    }
    
    // Email validation
    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    // Check if email already exists
    $emailCheck = "SELECT user_id FROM users WHERE email='$userEmail'";
    $emailResult = mysqli_query($conn, $emailCheck);
    if (mysqli_num_rows($emailResult) > 0) {
        $errors[] = "Email already registered";
    }
    
    // Phone validation (10-15 digits)
    if (!preg_match('/^[0-9]{10,15}$/', $phoneNum)) {
        $errors[] = "Please enter a valid phone number (10-15 digits)";
    }
    
    // Check if phone already exists
    $phoneCheck = "SELECT user_id FROM users WHERE phone='$phoneNum'";
    $phoneResult = mysqli_query($conn, $phoneCheck);
    if (mysqli_num_rows($phoneResult) > 0) {
        $errors[] = "Phone number already registered";
    }
    
    // Password validation
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    
    // Role validation
    if (empty($selectedRole)) {
        $errors[] = "Please select a role";
    }
    
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $getRoleId = "SELECT role_id FROM roles WHERE role_name='$selectedRole'";
        $roleRes = mysqli_query($conn, $getRoleId);
        $roleData = mysqli_fetch_assoc($roleRes);
        $rid = $roleData['role_id'];

        $insertUser = "INSERT INTO users (role_id, full_name, email, phone, password_hash) 
                       VALUES ('$rid', '$fullname', '$userEmail', '$phoneNum', '$hashedPassword')";

        if (mysqli_query($conn, $insertUser)) {
            $msg = "Registration successful! Please wait for admin approval to login.";
        } else {
            $msg = "Registration failed. Please try again.";
        }
    } else {
        $msg = implode("<br>", $errors);
    }
}

if (isset($_POST['login'])) {
    $loginEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $loginPass = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    $query = "SELECT u.user_id, u.full_name, u.email, u.password_hash, u.is_approved, u.is_blocked, r.role_name 
              FROM users u 
              JOIN roles r ON u.role_id = r.role_id 
              WHERE u.email = '$loginEmail' AND u.is_blocked = FALSE";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (!$user['is_approved']) {
            $msg = "Your account is pending approval. Please wait for admin approval.";
        } elseif (password_verify($loginPass, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role_name'];
            
            // Handle Remember Me
            if ($rememberMe) {
                // Create a secure token
                $token = bin2hex(random_bytes(32));
                // $hashedToken = password_hash($token, PASSWORD_DEFAULT); // This would be stored in DB for proper validation
                
                // Set cookies for 30 days
                setcookie('remember_me', $user['user_id'], time() + (30 * 24 * 60 * 60), '/', '', false, true); // 30 days, HttpOnly
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
            }

            switch ($user['role_name']) {
                case 'Waste Buyer':
                    header("Location: /wastio/buyer/dashboard.php");
                    exit;
                case 'Waste Seller':
                    header("Location: /wastio/seller/wastio-seller/dashboard.php");
                    exit;
                case 'Collection Agent':
                    header("Location: /wastio/agent/pickupagent/dashboard.php");
                    exit;
                case 'System Admin':
                    header("Location: /wastio/admin/dashboard.php");
                    exit;
            }
        } else {
            $msg = "Incorrect password.";
        }
    } else {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>

<body>
    <div class="container">

        <div class="header">
            <h1>♻ Wastio</h1>
            <p>Recycling Platform</p>
        </div>

        <?php if ($msg != "") { ?>
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
                    <div class="form-group remember-me">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember_me" id="rememberMe">
                            <span class="checkmark"></span>
                            <span class="checkbox-label">Remember me for 30 days</span>
                        </label>
                    </div>
                    <button type="submit" name="login">Login</button>
                </form>
                <div class="toggle-text">
                    Don't have an account? <a href="#" class="toggle-link" onclick="toggleForm(event)">Register here</a>
                </div>
            </div>

            <div class="form-container" id="registrationForm">
                <h3>Registration</h3>
                <form method="POST" id="regForm" onsubmit="return validateRegistration()">
                    <div class="form-group">
                        <input type="text" name="name" id="regName" placeholder="Full Name" required minlength="3">
                        <span class="error-msg" id="nameError"></span>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="regEmail" placeholder="Email Address" required>
                        <span class="error-msg" id="emailError"></span>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" id="regPhone" placeholder="Phone Number (10-15 digits)" required pattern="[0-9]{10,15}">
                        <span class="error-msg" id="phoneError"></span>
                    </div>
                    <div class="form-group">
                        <select name="role" id="regRole" required>
                            <option value="">Select Role</option>
                            <option>Waste Seller</option>
                            <option>Waste Buyer</option>
                            <option>Collection Agent</option>
                        </select>
                        <span class="error-msg" id="roleError"></span>
                    </div>
                    <div class="form-group">
                        <div class="password-input">
                            <input type="password" name="password" id="regPassword" placeholder="Password (min 8 characters)" required minlength="8">
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('regPassword', this)"></i>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-text" id="strengthText">Password strength</span>
                        </div>
                        <span class="error-msg" id="passwordError"></span>
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