<?php
session_start(); // Start the session

include 'db_connect.php'; 

$error_message = "";

// Check if the user clicked the login button or is just visiting the page for the first time
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Prepare the query securely (Prepared Statements)
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($connect, $sql);

    if ($stmt) {
        // Bind the email parameter to the statement
        mysqli_stmt_bind_param($stmt, "s", $email);
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Fetch the result
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        // 2. Verify the user and password
        if ($user && password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];

            header("Location: dashboard.php");
            exit();

        } else {
            $error_message = "The email or password are incorrect!";
        }
    } else {
        $error_message = "Database Error: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
</head>
<body>
    <h2>Log in</h2>
    
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>
        <p>Don't you have an account?<a href="register.php">Create new account</a></p>
        <button type="submit" name="login">Enter</button>
    </form>
</body>
</html>
