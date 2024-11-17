<?php
include 'navi.php';

include '../includes/db_connection.php'; // Database connection

// Initialize message for feedback
$message = '';

// Check if the form is submitted to add a new user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields exist in the POST request
    if (isset($_POST['full_name']) && isset($_POST['phone']) && isset($_POST['password'])) {
        // Collect and sanitize input data
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Generate an email (assuming a simple format: fullname@domain.com)
        $email = strtolower(str_replace(' ', '', $full_name)) . '@librarysystem.com';

        // Check if phone number already exists
        $check_stmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");
        $check_stmt->bind_param("s", $phone);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "Error: Phone number already exists.";
        } else {
            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO users (full_name, phone, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $phone, $email, $password);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "User added successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        }

        // Close the check statement
        $check_stmt->close();
    } else {
        $message = "Please fill all fields.";
    }
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_stmt->bind_param("i", $delete_id);

    if ($delete_stmt->execute()) {
        $message = "User deleted successfully!";
    } else {
        $message = "Error deleting user: " . $delete_stmt->error;
    }

    $delete_stmt->close();
}

// Fetch users from the database
$result = mysqli_query($conn, "SELECT * FROM users"); // Modify to match your table name
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel='stylesheet'>
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Manage Users</h2>

                <!-- Display User Addition/Deletion Message -->
                <?php if ($message): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <!-- Add New User Form -->
                <div class="card mb-5">
                    <div class="card-header">
                        <h4>Add New User</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name:</label>
                                <input type="text" id="full_name" name="full_name" class="form-control" required>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number:</label>
                                <input type="text" id="phone" name="phone" class="form-control" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Add User</button>
                        </form>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-header">
                        <h4>Registered Users</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTable">
                                <!-- Users Data Will Be Populated Here -->
                                <?php
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['full_name']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['phone']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-info' onclick='viewUser({$row['id']})'>View</button>
                                            <button class='btn btn-sm btn-warning' onclick='editUser({$row['id']})'>Edit</button>
                                            <a href='?delete_id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JS for handling actions -->
    <script>
        // Edit User
        function editUser(userId) {
            alert('Edit user with ID: ' + userId);
            // You can implement a modal or form to edit user details here
        }

        // View User
        function viewUser(userId) {
            alert('View details of user with ID: ' + userId);
            // You can show a modal with user details here
        }
    </script>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>

</html>