<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "wrh@12345";
$dbname = "dynamic_data_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table_name = $_POST['table_name'];

$sql = "DROP TABLE $table_name";

if ($conn->query($sql) === TRUE) {
    echo "Table deleted successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
