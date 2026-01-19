<?php
session_start();
session_unset();
session_destroy();

// Clear Remember Me cookies
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

header("Location: login.php");
exit();
?>