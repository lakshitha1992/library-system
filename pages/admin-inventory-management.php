<?php include 'navi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Inventory Management</h2>

        <!-- Inventory Form -->
        <form id="inventoryForm" method="POST" action="">
            <div class="mb-3">
                <label for="bookTitle" class="form-label">Book Title:</label>
                <select class="form-control" id="bookTitle" name="book_id" required>
                    <option value="">Select a Book</option>
                    <?php
                    include '../includes/db_connection.php'; // Include your database connection file
                    
                    // Fetch books for the dropdown
                    $bookQuery = "SELECT id, title FROM books";
                    $booksResult = mysqli_query($conn, $bookQuery);

                    while ($book = mysqli_fetch_assoc($booksResult)) {
                        echo "<option value='{$book['id']}'>{$book['title']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
            </div>
            <button type="submit" class="btn btn-primary">Add to Inventory</button>
        </form>

        <!-- Existing Inventory -->
        <div class="mt-4">
            <h4>Current Inventory:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Quantity</th>
                        <th>Genre</th>
                        <th>Publication Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if form has been submitted
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Get form data
                        $book_id = $_POST['book_id'];
                        $quantity = $_POST['quantity'];

                        // Insert new inventory entry
                        $stmt = $conn->prepare("INSERT INTO inventory (book_id, quantity) VALUES (?, ?)");
                        $stmt->bind_param("ii", $book_id, $quantity);
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Book added to inventory successfully!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error adding book to inventory: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }

                    // SQL query to fetch existing inventory details, including genres
                    $query = "
                        SELECT b.title, b.author, b.isbn, i.quantity, c.name AS genre, b.publication_date
                        FROM inventory i
                        JOIN books b ON i.book_id = b.id
                        JOIN book_genres bg ON b.id = bg.book_id
                        JOIN categories c ON bg.genre_id = c.id";
                    $result = mysqli_query($conn, $query);

                    // Display existing inventory
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['title']}</td>
                                    <td>{$row['author']}</td>
                                    <td>{$row['isbn']}</td>
                                    <td>{$row['quantity']}</td>
                                    <td>{$row['genre']}</td>
                                    <td>{$row['publication_date']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center alert alert-warning'>No inventory records found.</td></tr>";
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