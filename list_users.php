<?php
// Include the configuration file to use the database credentials
include('config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all submissions from the database
$sql = "SELECT id, name, email, web_link, media_file_path, submission_date FROM submissions";
$result = $conn->query($sql);

// Check for SQL query errors
if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>

<?php
// Close the connection
$conn->close();
?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery (for search functionality) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .table {
            margin-top: 20px;
        }
        .btn-download {
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            padding: 20px 0;
            background-color: #343a40;
            color: #fff;
            text-align: center;
        }
        .search-box {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Submissions List</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="my-4 text-center">Submissions List</h1>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
        </div>

        <?php if ($result->num_rows > 0): ?>
            <!-- Display the submissions list in a table with Bootstrap classes -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="submissionsTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Web Link</th>
                            <th>Media File</th>
                            <th>Submission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                            $media_file = $row['media_file_path'] ? "<a href='{$row['media_file_path']}' target='_blank' class='btn btn-sm btn-outline-primary'>Download</a>" : "No file";
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><a href="<?php echo $row['web_link']; ?>" target="_blank" class="btn btn-sm btn-outline-success">Visit Link</a></td>
                                <td><?php echo $media_file; ?></td>
                                <td><?php echo $row['submission_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>



</div class="container">
    <h1 class="text-center my-4">Submissions List</h1>
    <a href="download_users.php" class="btn btn-primary btn-download">Download</a>
    <table class="table table-bordered table-striped" id="submissionsTable">
        <!-- Table content here -->
    </table>
</div>

<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2023 Submissions List. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Search Functionality -->
    <script>
        $(document).ready(function () {
            $("#searchInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#submissionsTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>


