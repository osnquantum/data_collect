<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table_name = $_POST['table_name'];
    $columns = $_POST['columns'];

    // Create connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create table query
    $sql = "CREATE TABLE $table_name ($columns)";

    if ($conn->query($sql) === TRUE) {
        echo "Table '$table_name' created successfully! Redirecting to home page...";
        header("refresh:2; url=index.php"); // Redirect after 2 seconds
        exit();
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $conn->close();
}
?>

