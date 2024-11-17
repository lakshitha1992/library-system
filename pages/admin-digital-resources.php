<?php include 'navi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Resources</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Digital Resources</h2>

        <!-- Upload Form -->
        <form id="uploadForm" method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Select PDF File:</label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <!-- Resource List -->
        <h4 class="mt-4">Uploaded Resources</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Upload Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../includes/db_connection.php'; // Include your database connection file
                
                // Handle file upload
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $title = mysqli_real_escape_string($conn, $_POST['title']);
                    $description = mysqli_real_escape_string($conn, $_POST['description']);
                    $file = $_FILES['file'];

                    // Check file type
                    if ($file['type'] === 'application/pdf') {
                        // Read file contents
                        $fileData = file_get_contents($file['tmp_name']);
                        $fileData = mysqli_real_escape_string($conn, $fileData);

                        // Insert record into the database
                        $insertQuery = "INSERT INTO digital_resources (title, description, file_data) VALUES ('$title', '$description', '$fileData')";
                        if (mysqli_query($conn, $insertQuery)) {
                            echo "<div class='alert alert-success'>File uploaded successfully!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Only PDF files are allowed!</div>";
                    }
                }

                // Fetch and display uploaded resources
                $resourcesQuery = "SELECT * FROM digital_resources";
                $resourcesResult = mysqli_query($conn, $resourcesQuery);

                while ($row = mysqli_fetch_assoc($resourcesResult)) {
                    echo "<tr>
                            <td>{$row['title']}</td>
                            <td>{$row['description']}</td>
                            <td><a href='admin-digital-resources-download.php?id={$row['id']}'>Download</a></td>
                            <td>{$row['upload_date']}</td>
                          </tr>";
                }

                // Close the database connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>