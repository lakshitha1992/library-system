<?php
include '../includes/db_connection.php'; // Include your database connection file

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the file data from the database
    $query = "SELECT title, file_data FROM digital_resources WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $title = $row['title'];
        $fileData = $row['file_data'];

        // Set headers to force download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $title . '"');
        header('Content-Length: ' . strlen($fileData));

        // Output file content
        echo $fileData;
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}

mysqli_close($conn);
?>