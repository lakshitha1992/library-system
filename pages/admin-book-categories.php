<?php include 'navi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Book Categories</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Manage Book Categories</h2>

        <!-- Category Form -->
        <form id="categoryForm" method="POST" action="">
            <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name:</label>
                <input type="text" class="form-control" id="categoryName" name="categoryName" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>

        <!-- Existing Categories -->
        <div class="mt-4">
            <h4>Existing Categories:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Category Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    include '../includes/db_connection.php'; // Include your database connection file
                    
                    // Check if form has been submitted
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Get form data
                        $categoryName = $_POST['categoryName'];

                        // Prepare and execute SQL query to insert new category
                        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
                        $stmt->bind_param("s", $categoryName);

                        // Execute and provide feedback
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Category added successfully!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error adding category: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }

                    // SQL query to fetch existing categories
                    $result = mysqli_query($conn, "SELECT name FROM categories");

                    // Display existing categories
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='1' class='text-center'>No categories found.</td></tr>";
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>