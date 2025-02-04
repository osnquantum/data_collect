<?php
// Fetch data from the database

// Include the configuration file
include('config.php');

// Create a connection using the constants from config.php
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!";
}
$result = $conn->query("SELECT COUNT(*) as count, DATE(submission_date) as date FROM submissions GROUP BY DATE(submission_date)");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Submission Statistics</h1>
        <canvas id="submissionChart" width="400" height="200"></canvas>
    </div>

    <script>
        const data = <?php echo json_encode($data); ?>;
        const labels = data.map(row => row.date);
        const counts = data.map(row => row.count);

        const ctx = document.getElementById('submissionChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Submissions',
                    data: counts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
