<?php
session_start(); // Start the session

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: ../user.php"); // Redirect to login page
    exit();
}
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin-home.php">Library System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link" href="user-dashboard.php">Home</a>
                    </li>

                    <!-- Digital Resources -->
                    <li class="nav-item">
                        <a class="nav-link" href="user-download.php">Digital Resources</a>
                    </li>
                </ul>
                <!-- Logout Icon -->
                <form method="POST" class="d-flex">
                    <button class="btn btn-outline-light me-2" type="submit" name="logout" title="Logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>