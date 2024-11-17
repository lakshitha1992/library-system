<?php
session_start(); // Start the session
include 'includes/db_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $userId = $_POST['userId'];
    $password = $_POST['password'];

    // Prepare and execute SQL query to find user
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];

            // Redirect to user dashboard
            header("Location: pages/user-dashboard.php");
            exit();
        } else {
            $error = "Invalid User ID or Password!";
        }
    } else {
        $error = "Invalid User ID or Password!";
    }

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

    <!-- Style Sheets -->
    <link rel="stylesheet" href="assets/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="login shadow-drop-2-center">
        <div class="text-center">
            <h4 class="mb-4">Login: Library System</h4>
            <?php if (isset($error)) {
                echo "<div class='alert alert-danger'>$error</div>";
            } ?>
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
        <!--    <a href="pages/sign_up.php">Need an Account?</a> -->
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Library System. All rights reserved.</p>
            <p>The System is Designed by: Lakshitha D. Hemasinghe</p>
            <ul class="footer-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>