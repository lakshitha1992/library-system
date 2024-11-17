<?php include 'navi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reservation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Book Reservation</h2>

        <!-- Book Reservation Form -->
        <form id="reservationForm" method="POST" action="">
            <div class="mb-3">
                <label for="memberId" class="form-label">Member:</label>
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
                    // Fetch books for the dropdown, showing titles instead of ISBNs
                    $bookQuery = "SELECT id, title FROM books";
                    $booksResult = mysqli_query($conn, $bookQuery);

                    while ($book = mysqli_fetch_assoc($booksResult)) {
                        echo "<option value='{$book['id']}'>{$book['title']}</option>"; // Show book title
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="reservationDate" class="form-label">Reservation Date:</label>
                <input type="date" class="form-control" id="reservationDate" name="reservationDate" required>
            </div>
            <button type="submit" class="btn btn-primary">Reserve Book</button>
        </form>

        <!-- Reservation Transaction Results -->
        <div class="mt-4">
            <h4>Reservations:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Book Name</th>
                        <th>Reservation Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if form has been submitted
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Get form data
                        $user_id = $_POST['user_id'];
                        $book_id = $_POST['book_id'];
                        $reservationDate = $_POST['reservationDate'];

                        // Prepare and execute SQL query to insert reservation
                        $stmt = $conn->prepare("INSERT INTO reservations (user_id, book_id, reservation_date, status) VALUES (?, ?, ?, 'active')");
                        $stmt->bind_param("iis", $user_id, $book_id, $reservationDate);
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Book reserved successfully!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error reserving book: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }

                    // SQL query to fetch existing reservations
                    $query = "
                        SELECT u.full_name, b.title AS book_name, r.reservation_date, r.status
                        FROM reservations r
                        JOIN users u ON r.user_id = u.id
                        JOIN books b ON r.book_id = b.id";
                    $result = mysqli_query($conn, $query);

                    // Display existing reservations
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['full_name']}</td>
                                <td>{$row['book_name']}</td>
                                <td>{$row['reservation_date']}</td>
                                <td>{$row['status']}</td>
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