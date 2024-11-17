<?php
include 'user_navi.php'; // Include the navigation bar

// Include database connection
include '../includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$userId = $_SESSION['user_id']; // Get user ID from session

// Handle marking notifications as read
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notification_id'])) {
    $notificationId = $_POST['notification_id'];

    // Update the notification status to read
    $updateQuery = "UPDATE notifications SET status = 'read' WHERE id = ? AND user_id = ?";
    if ($updateStmt = $conn->prepare($updateQuery)) {
        $updateStmt->bind_param("ii", $notificationId, $userId);

        if ($updateStmt->execute()) {
            // Optional: You can set a success message here
            // echo "Notification marked as read.";
        } else {
            // Log or display an error message
            echo "Error marking notification as read: " . $conn->error;
        }
        $updateStmt->close();
    } else {
        // Log or display an error message
        echo "Error preparing statement: " . $conn->error;
    }
}

// Check for unpaid fines
$finesQuery = "SELECT SUM(amount) AS total_fines FROM fines WHERE user_id = ? AND status = 'unpaid'";
$finesStmt = $conn->prepare($finesQuery);
$finesStmt->bind_param("i", $userId);
$finesStmt->execute();
$finesResult = $finesStmt->get_result();
$finesData = $finesResult->fetch_assoc();
$totalFines = $finesData['total_fines'] ?? 0; // Get total fines

$finesStmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">User Dashboard</h2>

        <!-- Read Books Section -->
        <div class="mb-4">
            <h4>Books You Have Read:</h4>
            <ul class="list-group">
                <?php
                // Fetching read books
                $readBooksQuery = "
                    SELECT b.title 
                    FROM borrowed_books bb
                    JOIN books b ON bb.book_id = b.id
                    WHERE bb.user_id = ? AND bb.return_date IS NOT NULL";
                $stmt = $conn->prepare($readBooksQuery);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $readBooksResult = $stmt->get_result();

                while ($book = $readBooksResult->fetch_assoc()) {
                    echo "<li class='list-group-item'>{$book['title']}</li>";
                }

                $stmt->close();
                ?>
            </ul>
        </div>

        <!-- Currently Borrowed Books Section -->
        <div class="mb-4">
            <h4>Currently Borrowed Books:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetching currently borrowed books
                    $borrowedBooksQuery = "
                        SELECT b.title, bb.borrow_date, bb.return_date 
                        FROM borrowed_books bb
                        JOIN books b ON bb.book_id = b.id
                        WHERE bb.user_id = ? AND bb.return_date IS NULL";
                    $stmt = $conn->prepare($borrowedBooksQuery);
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $borrowedBooksResult = $stmt->get_result();

                    while ($row = $borrowedBooksResult->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['title']}</td>
                                <td>{$row['borrow_date']}</td>
                                <td>" . ($row['return_date'] ? $row['return_date'] : 'Not yet returned') . "</td>
                              </tr>";
                    }

                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Notifications Section -->
        <div class="mb-4">
            <h4>Notifications:</h4>
            <ul class="list-group">
                <?php
                // Fetching notifications
                $notificationsQuery = "
                    SELECT id, message, created_at, status 
                    FROM notifications 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC";
                $stmt = $conn->prepare($notificationsQuery);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $notificationsResult = $stmt->get_result();

                while ($notification = $notificationsResult->fetch_assoc()) {
                    $status = $notification['status'] === 'read' ? ' (Read)' : ' (Unread)';
                    echo "<li class='list-group-item'>{$notification['message']} - <small>{$notification['created_at']}</small>{$status}";

                    // Add a read button if the notification is unread
                    if ($notification['status'] === 'unread') {
                        echo "<form method='POST' action='user-dashboard.php' style='display:inline;'>
                                <input type='hidden' name='notification_id' value='{$notification['id']}'>
                                <button class='btn btn-link btn-sm' type='submit'>Mark as Read</button>
                              </form>";
                    }

                    echo "</li>"; // Closing the list item
                }

                $stmt->close();
                ?>
            </ul>
        </div>


        <!-- Fines Section -->
        <div class="mb-4">
            <h4>Your Fines:</h4>
            <ul class="list-group">
                <?php
                if ($totalFines > 0) {
                    echo "<li class='list-group-item'>You have unpaid fines totaling: $$totalFines</li>";
                    echo "<li class='list-group-item'><span class='badge bg-danger'>You have unpaid fines</span></li>"; // Added badge for unpaid fines
                } else {
                    echo "<li class='list-group-item'>No unpaid fines.</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>