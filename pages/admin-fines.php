<?php include 'navi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines & Penalties</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Fines & Penalties Management</h2>

        <!-- Fines Form -->
        <form id="finesForm" method="POST" action="">
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
                <label for="fineAmount" class="form-label">Fine Amount:</label>
                <input type="number" step="0.01" class="form-control" id="fineAmount" name="fineAmount" required>
            </div>
            <div class="mb-3">
                <label for="dateIssued" class="form-label">Date Issued:</label>
                <input type="date" class="form-control" id="dateIssued" name="dateIssued" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Fine</button>
        </form>

        <!-- Fines Transaction Results -->
        <div class="mt-4">
            <h4>Existing Fines:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Fine Amount</th>
                        <th>Date Issued</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if form has been submitted
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['payFine'])) {
                        // Get form data
                        $user_id = $_POST['user_id'];
                        $fineAmount = $_POST['fineAmount'];
                        $dateIssued = $_POST['dateIssued'];

                        // Prepare and execute SQL query to insert fine
                        $stmt = $conn->prepare("INSERT INTO fines (user_id, amount, created_at) VALUES (?, ?, ?)");
                        $stmt->bind_param("ids", $user_id, $fineAmount, $dateIssued);
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Fine added successfully!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error adding fine: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }

                    // Check if pay fine form has been submitted
                    if (isset($_POST['payFine'])) {
                        $fineId = $_POST['fineId']; // Get the fine ID from the form
                        // Update the status of the fine to 'paid'
                        $updateStmt = $conn->prepare("UPDATE fines SET status = 'paid' WHERE id = ?");
                        $updateStmt->bind_param("i", $fineId);
                        if ($updateStmt->execute()) {
                            echo "<div class='alert alert-success'>Fine marked as paid!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error updating fine: " . $updateStmt->error . "</div>";
                        }
                        $updateStmt->close();
                    }

                    // SQL query to fetch existing fines
                    $query = "
                        SELECT f.id, u.full_name, f.amount, f.created_at, f.status
                        FROM fines f
                        JOIN users u ON f.user_id = u.id";
                    $result = mysqli_query($conn, $query);

                    // Display existing fines
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['full_name']}</td>
                                <td>\${$row['amount']}</td>
                                <td>{$row['created_at']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='fineId' value='{$row['id']}'>
                                        <button type='submit' name='payFine' class='btn btn-success'>Pay Fine</button>
                                    </form>
                                </td>
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