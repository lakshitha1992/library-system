<?php include 'navi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporting & Analytics</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Reporting & Analytics</h2>

        <!-- User Activity Report -->
        <h4>User Activity Report</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Borrowed Books</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../includes/db_connection.php'; // Include your database connection file
                
                // User Activity Report Query
                $userActivityQuery = "
                    SELECT u.id AS user_id, u.full_name, COUNT(b.id) AS borrowed_books
                    FROM users u
                    LEFT JOIN borrowed_books b ON u.id = b.user_id
                    GROUP BY u.id;
                ";
                $userActivityResult = mysqli_query($conn, $userActivityQuery);

                while ($row = mysqli_fetch_assoc($userActivityResult)) {
                    echo "<tr>
                            <td>{$row['user_id']}</td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['borrowed_books']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Book Inventory Report -->
        <h4>Book Inventory Report</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total Books</th>
                    <th>Borrowed Books</th>
                    <th>Available Books</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Book Inventory Query
                $bookInventoryQuery = "
                    SELECT COUNT(*) AS total_books,
                    SUM(CASE WHEN status = 'borrowed' THEN 1 ELSE 0 END) AS borrowed_books,
                    SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) AS available_books
                    FROM books
                ";
                $bookInventoryResult = mysqli_query($conn, $bookInventoryQuery);
                $inventory = mysqli_fetch_assoc($bookInventoryResult);
                echo "<tr>
                        <td>{$inventory['total_books']}</td>
                        <td>{$inventory['borrowed_books']}</td>
                        <td>{$inventory['available_books']}</td>
                      </tr>";
                ?>
            </tbody>
        </table>

        <!-- Fines and Penalties Report -->
        <h4>Fines and Penalties Report</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total Fines Collected</th>
                    <th>Total Penalties</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fines Query
                $finesQuery = "
                    SELECT SUM(amount) AS total_fines, COUNT(*) AS total_penalties
                    FROM fines
                ";
                $finesResult = mysqli_query($conn, $finesQuery);
                $finesData = mysqli_fetch_assoc($finesResult);
                echo "<tr>
                        <td>{$finesData['total_fines']}</td>
                        <td>{$finesData['total_penalties']}</td>
                      </tr>";
                ?>
            </tbody>
        </table>

        <!-- Book Reservations Report -->
        <h4>Book Reservations Report</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Total Reservations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Book Reservations Query
                $reservationsQuery = "
                    SELECT b.title, COUNT(r.id) AS total_reservations
                    FROM books b
                    LEFT JOIN reservations r ON b.id = r.book_id
                    GROUP BY b.id;
                ";
                $reservationsResult = mysqli_query($conn, $reservationsQuery);

                while ($row = mysqli_fetch_assoc($reservationsResult)) {
                    echo "<tr>
                            <td>{$row['title']}</td>
                            <td>{$row['total_reservations']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>