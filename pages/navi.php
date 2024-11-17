<?php
session_start(); // Start the session

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../admin.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/navi.css">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin-home.php">Library System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="admin-home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-registration.php">User Management</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="bookDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Book Management</a>
                        <ul class="dropdown-menu" aria-labelledby="bookDropdown">
                            <li><a class="dropdown-item" href="admin-book-cataloging.php">Add New Book</a></li>
                            <li><a class="dropdown-item" href="admin-search-books.php">Search Books</a></li>
                            <li><a class="dropdown-item" href="admin-book-categories.php">Book Categories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-inventory-management.php">Inventory Management</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="borrowingDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Borrowing & Lending</a>
                        <ul class="dropdown-menu" aria-labelledby="borrowingDropdown">
                            <li><a class="dropdown-item" href="admin-borrow-book.php">Borrow Books</a></li>
                            <li><a class="dropdown-item" href="admin-return-book.php">Return Books</a></li>
                            <li><a class="dropdown-item" href="admin-reservations.php">Reservations</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-fines.php">Fines & Penalties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-send-notifications.php">Notifications & Alerts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-reporting-analytics.php">Reporting & Analytics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-digital-resources.php">Digital Resources</a>
                    </li>
                </ul>

                <!-- Logout Button -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?logout=true">Logout (<?php echo $_SESSION['full_name']; ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>