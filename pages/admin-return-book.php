<?php include 'navi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Return Book</h2>

        <!-- Return Book Form -->
        <form id="returnForm" method="POST" action="">
            <div class="mb-3">
                <label for="memberId" class="form-label">Member ID:</label>
                <select class="form-control" id="memberId" name="user_id" required>
                    <option value="">Select a Member</option>
                    <?php
                    include '../includes/db_connection.php'; // Include your database connection file
                    
                    // Fetch members for the dropdown
                    $userQuery = "SELECT id, full_name FROM users";
                    $usersResult = mysqli_query($conn, $userQuery);

                    while ($user = mysqli_fetch_assoc($usersResult)) {
                        echo "<option value='{$user['id']}'>{$user['full_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="bookId" class="form-label">Book Name:</label>
                <select class="form-control" id="bookId" name="book_id" required>
                    <option value="">Select a Book</option>
                    <?php
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
                <label for="returnDate" class="form-label">Return Date:</label>
                <input type="date" class="form-control" id="returnDate" name="returnDate" required>
            </div>
            <button type="submit" class="btn btn-primary">Return Book</button>
        </form>

        <!-- Return Transaction Results -->
        <div class="mt-4">
            <h4>Return Transactions:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Member ID</th>
                        <th>Book Name</th>
                        <th>Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Handle form submission
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['user_id'], $_POST['book_id'], $_POST['returnDate'])) {
                            // Get form data
                            $user_id = $_POST['user_id'];
                            $book_id = $_POST['book_id'];
                            $returnDate = $_POST['returnDate'];

                            // Prepare and execute SQL query to update borrow transaction
                            $stmt = $conn->prepare("UPDATE borrowed_books SET return_date = ? WHERE user_id = ? AND book_id = ? AND return_date IS NULL");
                            $stmt->bind_param("sii", $returnDate, $user_id, $book_id);

                            if ($stmt->execute()) {
                                if ($stmt->affected_rows > 0) {
                                    echo "<div class='alert alert-success'>Book returned successfully!</div>";
                                } else {
                                    echo "<div class='alert alert-warning'>No active borrow transaction found for this book and member.</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Error returning book: " . $stmt->error . "</div>";
                            }
                            $stmt->close();
                        } else {
                            echo "<div class='alert alert-danger'>Please fill out all fields.</div>";
                        }
                    }

                    // SQL query to fetch existing return transactions
                    $query = "
                        SELECT u.full_name AS member_name, u.id AS member_id, b.title AS book_name, bb.return_date
                        FROM borrowed_books bb
                        JOIN users u ON bb.user_id = u.id
                        JOIN books b ON bb.book_id = b.id
                        WHERE bb.return_date IS NOT NULL";
                    $result = mysqli_query($conn, $query);

                    // Display existing return transactions
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['member_name']}</td>
                                <td>{$row['member_id']}</td>
                                <td>{$row['book_name']}</td>
                                <td>{$row['return_date']}</td>
                              </tr>";
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