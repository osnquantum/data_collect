<?php
session_start(); // Start the session
include('config.php');

// Create a connection using the constants from config.php
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input safely
$input_username = trim($_POST['username']);
$input_password = trim($_POST['password']);

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
$stmt->bind_param("s", $input_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($input_password, $row['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $row['id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Invalid password!";
    }
} else {
    echo "Invalid username!";
}

$stmt->close();
$conn->close();
?>

