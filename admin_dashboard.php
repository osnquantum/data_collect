<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Include the database configuration file
include('config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>

        <h2>Database Details</h2>
        <?php
        // Display database name
        echo "<p><strong>Database Name:</strong> " . DB_DATABASE . "</p>";

        // Display tables and columns
        $sql = "SHOW TABLES FROM " . DB_DATABASE;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='accordion' id='tableAccordion'>";
            while ($row = $result->fetch_assoc()) {
                $tableName = $row["Tables_in_" . DB_DATABASE];
                echo "<div class='accordion-item'>";
                echo "<h2 class='accordion-header'>";
                echo "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse" . $tableName . "' aria-expanded='false' aria-controls='collapse" . $tableName . "'>";
                echo $tableName;
                echo "</button>";
                echo "</h2>";
                echo "<div id='collapse" . $tableName . "' class='accordion-collapse collapse' data-bs-parent='#tableAccordion'>";
                echo "<div class='accordion-body'>";

                $columnSql = "SHOW COLUMNS FROM " . $tableName;
                $columnResult = $conn->query($columnSql);
                if ($columnResult->num_rows > 0) {
                    echo "<ul class='list-group'>";
                    while ($columnRow = $columnResult->fetch_assoc()) {
                        echo "<li class='list-group-item'>" . $columnRow["Field"] . " (" . $columnRow["Type"] . ")</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "0 results";
                }

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "0 results";
        }
        ?>

        <hr>

        <h2>Database Designer</h2>
        <form action="create_table.php" method="post">
            <div class="mb-3">
                <label for="table_name" class="form-label">Table Name</label>
                <input type="text" class="form-control" id="table_name" name="table_name" required>
            </div>
            <div class="mb-3">
                <label for="columns" class="form-label">Columns (comma-separated, e.g., id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255))</label>
                <textarea class="form-control" id="columns" name="columns" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Table</button>
        </form>

        <hr>

        <h2>Modify Table</h2>
        <form action="add_column.php" method="post">
            <div class="mb-3">
                <label for="table_name_modify" class="form-label">Table Name</label>
                <input type="text" class="form-control" id="table_name_modify" name="table_name_modify" required>
            </div>
            <div class="mb-3">
                <label for="column_definition" class="form-label">Column Definition (e.g., new_column INT)</label>
                <input type="text" class="form-control" id="column_definition" name="column_definition" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Column</button>
        </form>

        <hr>

        <h2>Delete Table</h2>
        <form action="delete_table.php" method="post">
            <div class="mb-3">
                <label for="table_name_delete" class="form-label">Table Name</label>
                <input type="text" class="form-control" id="table_name_delete" name="table_name_delete" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete Table</button>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
