<?php
session_start();
require_once 'db_connect.php'; // Include database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = "";
$success_message = "";

// Handle adding a new note when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_note'])) {
    // 1. Validation & Sanitization
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error_message = "All fields are required!";
    } else {
        // Use Prepared Statements for complete security and prevention of SQL Injection
        $stmt = $connect->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $content);
        
        if ($stmt->execute()) {
            $success_message = "Note added successfully!";
        } else {
            $error_message = "An error occurred, please try again.";
        }
        $stmt->close();
    }
}

// Fetch only this user's notes from the database
$stmt = $connect->prepare("SELECT title, content, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Notes</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>You are logged in securely.</p>
    <a href="logout.php">Log Out</a>

    <hr>

    <h3>Add New Note </h3>
    <?php if (!empty($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
    <?php if (!empty($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>

    <form action="dashboard.php" method="POST">
        <label>Note Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Note Content:</label><br>
        <textarea name="content" rows="4" required></textarea><br><br>

        <button type="submit" name="add_note">Save Note</button>
    </form>

    <hr>

    <h4>Your Personal Notes >> </h4>
    <?php if (empty($notes)): ?>
        <p>No notes found yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <strong><?php echo htmlspecialchars($note['title']); ?></strong>
                    <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                    <small>Created at: <?php echo $note['created_at']; ?></small>
                </li>
                <hr style="width: 50%; text-align: left; margin-left: 0;">
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>