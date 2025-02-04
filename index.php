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

// Fetch the list of tables
$sql = "SHOW TABLES";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Collection App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome to the Data Collection App</h1>

        <!-- Displaying the Data Collection Form -->
        <h2>Data Collection Form</h2>
        <form action="submit.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="web_link" class="form-label">Web Link</label>
                <input type="url" class="form-control" id="web_link" name="web_link" required>
            </div>
            <div class="mb-3">
                <label for="media_file" class="form-label">Upload Media File</label>
                <input type="file" class="form-control" id="media_file" name="media_file[]" accept="image/*,video/*,application/pdf" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <hr>

        <!-- Navigation Links -->
        <h2>Navigate to Other Pages</h2>
        <div class="list-group">
            <a href="submit.php" class="list-group-item list-group-item-action">Submit Data</a>
            <a href="add_column.php" class="list-group-item list-group-item-action">Add Column to Table</a>
            <a href="create_table.php" class="list-group-item list-group-item-action">Create New Table</a>
            <a href="list_users.php" class="list-group-item list-group-item-action">View Users</a>
            <a href="delete_table.php" class="list-group-item list-group-item-action">Delete Table</a>
            <a href="ststistics.php" class="list-group-item list-group-item-action">View Statistics</a>
            <a href="export.php" class="list-group-item list-group-item-action">Export Data</a>
        </div>

        <hr>

        <!-- Display List of Tables -->
        <h2>Existing Tables</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $table_name = $row['Tables_in_' . DB_DATABASE];
                
                // Fetch and display data from the table if it has Name and Sex columns
                $data_sql = "SELECT name, sex FROM $table_name";
                $data_result = $conn->query($data_sql);

                echo "<h3>Table: $table_name</h3>";
                if ($data_result->num_rows > 0) {
                    echo "<table class='table'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Sex</th>
                                </tr>
                            </thead>
                            <tbody>";
                    while ($data_row = $data_result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $data_row['name'] . "</td>
                                <td>" . $data_row['sex'] . "</td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "No data found for this table.";
                }
            }
        } else {
            echo "No tables found.";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
