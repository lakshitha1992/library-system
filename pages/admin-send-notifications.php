<?php include 'navi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notifications</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Send Notifications</h2>

        <!-- Notification Form -->
        <form id="notificationForm" method="POST" action="">
            <div class="mb-3">
                <label for="memberId" class="form-label">Member Name:</label>
                <select class="form-control" id="memberId" name="userId" required>
                    <option value="">Select a Member</option>
                    <?php
                    include '../includes/db_connection.php'; // Include your database connection file
                    
                    // Fetch members for the dropdown
                    $userQuery = "SELECT id, full_name FROM users"; // Adjust the table name as needed
                    $usersResult = mysqli_query($conn, $userQuery);

                    while ($user = mysqli_fetch_assoc($usersResult)) {
                        echo "<option value='{$user['id']}'>{$user['full_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Notification</button>
        </form>

        <!-- Notification Result -->
        <div class="mt-4">
            <h4>Existing Notifications:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Message</th>
                        <th>Date Sent</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if form has been submitted
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Get form data
                        $userId = $_POST['userId'];
                        $message = $_POST['message'];

                        // Prepare and execute SQL query to insert notification
                        $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
                        $stmt->bind_param("is", $userId, $message);
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Notification sent successfully!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error sending notification: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }

                    // SQL query to fetch existing notifications
                    $result = mysqli_query($conn, "SELECT n.id, n.user_id, n.message, n.created_at, n.status FROM notifications n ORDER BY n.created_at DESC");

                    // Display existing notifications
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['user_id']}</td>
                                <td>{$row['message']}</td>
                                <td>{$row['created_at']}</td>
                                <td>" . ($row['status'] === 'read' ? 'Read' : 'Unread') . "</td>
                                <td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='notificationId' value='{$row['id']}'>
                                        <button type='submit' name='markAsRead' class='btn btn-secondary'>Mark as Read</button>
                                    </form>
                                </td>
                              </tr>";
                    }

                    // Check if mark as read form has been submitted
                    if (isset($_POST['markAsRead'])) {
                        $notificationId = $_POST['notificationId']; // Get the notification ID
                        // Update the status of the notification to 'read'
                        $updateStmt = $conn->prepare("UPDATE notifications SET status = 'read' WHERE id = ?");
                        $updateStmt->bind_param("i", $notificationId);
                        if ($updateStmt->execute()) {
                            echo "<div class='alert alert-success'>Notification marked as read!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error updating notification: " . $updateStmt->error . "</div>";
                        }
                        $updateStmt->close();
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