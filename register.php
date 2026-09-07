<?php 
require 'db_connect.php';

$success_message = ""; // Variable to store the success state
$error_message = "";   // Variable for any error if it occurs (like a duplicate email)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Receiving data and storing in variables 
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username , email, password) VALUES (?,?,?)";
    $stmt = mysqli_prepare($connect, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            // If registration succeeds, set this variable to true to display the success message and button below
            $success_message = true;
        } else {
            // Smart handling if the email is already registered so the error doesn't blow up in the user's face
            if (mysqli_errno($connect) == 1062) {
                $error_message = "This email address is already registered. Please use another email.";
            } else {
                $error_message = "An error occurred: " . mysqli_stmt_error($stmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h2>Register New Account</h2>
    
    <!-- If registration is successful, show the success message and login button, and hide the form -->
    <?php if ($success_message): ?>
        <div>
            <h3>Account registered successfully! </h3>
            <p>Welcome <strong><?php echo htmlspecialchars($username); ?></strong>, you can now proceed to log in.</p>
            <br>
            <a href="login.php">Log In</a>
        </div>
    <?php else: ?>

        <!-- If there is an error (like a duplicate email), display it here -->
        <?php if (!empty($error_message)): ?>
            <p style="color: red; font-weight: bold;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- The form sends data to the same page via POST -->
        <form action="register.php" method="POST">  
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Register</button>
        </form>
    <?php endif; ?>

</body>
</html>