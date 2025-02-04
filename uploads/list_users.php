<?php
// Include the configuration file to use the database credentials
include('config.php');

// Create a connection using the constants from config.php
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all users from the database
$sql = "SELECT id, name, email FROM users"; // Adjust the columns as per your table
$result = $conn->query($sql);

// Check if there are users in the database
if ($result->num_rows > 0) {
    // Display the user list in a table
    echo "<h1>User List</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
              </tr>";
    }
    echo "</table>";
    
    // Provide a download link for the CSV file
    echo "<br><a href='download_users.php' class='btn btn-primary'>Download User List as CSV</a>";
} else {
    echo "No users found.";
}

// Close the connection
$conn->close();
?>

