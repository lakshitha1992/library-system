<?php
session_start(); // Start the session
$message = '';

// Include the database connection
include '../includes/db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $fullName = $_POST['fullName'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $email = strtolower(str_replace(' ', '', $fullName)) . '@librarysystem.com'; // Generate a sample email

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO admins (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $email, $phone, $password);

    // Execute the statement
    if ($stmt->execute()) {
        // Get the last inserted ID
        $adminId = $stmt->insert_id; // Get the newly generated Admin ID
        $message = "Registration successful! Your Admin ID is: " . $adminId;
        $_SESSION['adminId'] = $adminId; // Store the Admin ID in the session
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration: Library System</title>

    <!-- Style Sheets -->
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="sign_up shadow-drop-2-center">
        <div class="text-center">
            <h4 class="mb-4">Admin Registration: Library System</h4>

            <!-- Display Admin ID message -->
            <?php if ($message): ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Admin Registration Form -->
            <form method="POST" action="">
                <!-- Full Name -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="fullName" placeholder="Full Name" required>
                    <label for="fullName">Full Name</label>
                </div>

                <!-- Phone Number -->
                <div class="form-floating mb-3">
                    <input type="tel" class="form-control" name="phone" placeholder="Phone No" pattern="[0-9]{10}"
                        required>
                    <label for="phone">Phone No</label>
                </div>

                <!-- Password -->
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <!-- Sign Up Button -->
                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>
        </div>

        <a href="../index.php">Login!</a>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>