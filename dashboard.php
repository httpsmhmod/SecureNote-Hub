<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to your Dashboard, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>You are successfully logged in securely.</p>

    <!-- log out button -->
    <a href="logout.php">Logout</a>
</body>
</html>