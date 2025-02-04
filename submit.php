<?php
// Database connection (adjust these variables to your setup)

include('config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form data (ensure fields are set and not empty)
if (isset($_POST['name'], $_POST['email'], $_POST['web_link'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $web_link = $_POST['web_link'];
} else {
    echo "Missing form data!<br>";
    exit;  // Stop execution if required data is missing
}

// Handle multiple file uploads
$media_file_paths = [];  // Store file paths of uploaded files

// Loop through each uploaded file
foreach ($_FILES['media_file']['name'] as $index => $file_name) {
    // Check if the file has been uploaded successfully (error == 0)
    if ($_FILES['media_file']['error'][$index] == 0) {
        $target_dir = "uploads/";

        // Get file extension
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        // Create a unique file name using the current timestamp and random number
        $new_file_name = time() . '_' . rand(1000, 9999) . '.' . $file_extension;

        // Target file path
        $target_file = $target_dir . $new_file_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['media_file']['tmp_name'][$index], $target_file)) {
            $media_file_paths[] = $target_file;  // Store the file path in the array
            echo "File uploaded successfully: " . $target_file . "<br>";
        } else {
            echo "Failed to upload the file: " . $file_name . "<br>";
        }
    } else {
        echo "Error in file upload: " . $file_name . "<br>";
    }
}

// Prepare SQL query using prepared statements (for security)
$sql = $conn->prepare("INSERT INTO submissions (name, email, web_link, media_file_path) VALUES (?, ?, ?, ?)");
foreach ($media_file_paths as $file_path) {
    $sql->bind_param("ssss", $name, $email, $web_link, $file_path);
    // Execute the query for each file path
    if ($sql->execute()) {
        echo "Data submitted successfully with file: " . $file_path . "<br>";
    } else {
        echo "Error: " . $sql->error . "<br>";
    }
}

// Close the connection
$conn->close();
?>

