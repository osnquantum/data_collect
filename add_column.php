<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include('config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//table add code 
$table_name_modify = $_POST['table_name_modify'];
$column_definition = $_POST['column_definition'];



list($column_name, $column_type) = explode(" ", $column_definition, 2);


echo "Column Name: " . $column_name . "<br>";
echo "Column Type: " . $column_type . "<br>";

echo "Table Name: " . $table_name_modify . "<br>"; 

$sql = "ALTER TABLE $table_name_modify ADD COLUMN $column_name $column_type";

if ($conn->query($sql) === TRUE) {
    echo "Column added successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

