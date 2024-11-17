<?php
session_start(); // Start the session
include 'includes/db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $userId = $_POST['userId'];
    $password = $_POST['password'];

    // Prepare SQL query to find user
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables if password is correct
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];

            // Redirect to the user dashboard
            header("Location: pages/admin-home.php");
            exit();
        } else {
            $error = "Invalid User ID or Password!";
        }
    } else {
        $error = "Invalid User ID or Password!";
    }

    // Close statement and connection
    $stmt->close();
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login: Library System</title>

    <!-- Bootstrap and custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>
    <div class="login shadow-drop-2-center">
        <div class="text-center">
            <h4 class="mb-4">Login: Library System</h4>
            <!-- Display error message if login fails -->
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>

            <!-- Login form -->
            <form method="POST" action="">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput" name="userId" placeholder="User ID"
                        required>
                    <label for="floatingInput">User ID</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" name="password"
                        placeholder="Password" required>
                    <label for="floatingPassword">Password</label>
                </div>

                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
        </div>
        <!--     <a href="pages/sign_up.php">Need an Account?</a> -->
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 Library System. All rights reserved.</p>
            <p>Designed by: Lakshitha D. Hemasinghe</p>
            <ul class="footer-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>