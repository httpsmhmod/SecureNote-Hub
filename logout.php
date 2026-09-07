<?php
session_start(); // 1. Start the session so the server knows which session to close

// 2. Destroy all session variables that were stored (like user_id and user_name)
$_SESSION = array();

// 3. Clear the session cookie from the user's browser to ensure the session is completely terminated
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destroy the session file itself on the server (delete the file completely)
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
</head>
<body>
    <h2>Logged out successfully! </h2>
    <p>Thank you for using our system, You have been safely logged out.</p>
    
    <!-- Link to take the user back to the login page -->
    <a href="login.php">Log in again?</a>
    <br><br>
    <!-- Link to register a new account -->
    <a href="register.php">Create a new account?</a>
</body>
</html>