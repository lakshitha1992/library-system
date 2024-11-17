<?php
include 'user_navi.php'; // Include the navigation bar
include '../includes/db_connection.php'; // Include the database connection file

// Fetch digital resources
$sql = "SELECT id, title, description, file_data, upload_date FROM digital_resources";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
    <title>Digital Resources</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Digital Resources</h1>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>

                    <th>Title</th>
                    <th>Description</th>
                    <th>Upload Date</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Create a temporary file to display as a thumbnail
                        $file_data = $row['file_data'];
                        $file_name = "resource_{$row['id']}.pdf"; // Generate a temporary file name
                
                        // Create a temporary file for display
                        if (file_put_contents($file_name, $file_data) === false) {
                            echo "<tr>
                                    <td colspan='5' class='text-center'>Error creating file for ID {$row['id']}</td>
                                  </tr>";
                            continue; // Skip to the next iteration if file creation fails
                        }

                        echo "<tr>
                                
                                <td>{$row['title']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['upload_date']}</td>
                                <td><a href='$file_name' class='btn btn-primary' download>Download</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No resources found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>

<?php
$conn->close();
?>