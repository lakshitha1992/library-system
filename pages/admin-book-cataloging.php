<?php
// Start output buffering
ob_start();

include 'navi.php';
include '../includes/db_connection.php'; // Include your database connection file

// Initialize message for feedback
$message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publication_date = $_POST['pubDate'];
    $language = $_POST['language'];

    // Handle file upload for the book cover image
    $cover_image = null;
    if (isset($_FILES['bookCover']) && $_FILES['bookCover']['error'] === UPLOAD_ERR_OK) {
        // Get file content and ensure it's an image
        $cover_image = file_get_contents($_FILES['bookCover']['tmp_name']);
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, publication_date, language, cover_image) VALUES (?, ?, ?, ?, ?, ?)");

    // Bind parameters, all as strings
    $stmt->bind_param("ssssss", $title, $author, $isbn, $publication_date, $language, $cover_image);

    // Execute the statement
    if ($stmt->execute()) {
        $book_id = $stmt->insert_id; // Get the inserted book's ID

        // Insert selected genres into the book_genres table
        if (!empty($_POST['genres'])) {
            foreach ($_POST['genres'] as $genre_id) {
                $genre_stmt = $conn->prepare("INSERT INTO book_genres (book_id, genre_id) VALUES (?, ?)");
                $genre_stmt->bind_param("ii", $book_id, $genre_id);
                $genre_stmt->execute();
                $genre_stmt->close();
            }
        }

        $message = "Book added successfully!";
        // Redirect to the add book page with a success message
        header("Location: admin-book-cataloging.php?message=" . urlencode($message));
        exit();
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Fetch genres for the dropdown
$genresQuery = "SELECT id, name FROM categories";
$genresResult = mysqli_query($conn, $genresQuery);

// Display the success/error message
if (isset($_GET['message'])) {
    echo '<div class="alert alert-info">' . htmlspecialchars($_GET['message']) . '</div>';
}

ob_end_flush(); // Flush the output buffer and turn off output buffering
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
    <style>
        .tag {
            display: inline-block;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 5px 10px;
            margin: 5px;
        }

        .tag .remove-tag {
            margin-left: 5px;
            cursor: pointer;
            color: white;
        }

        .tag-container {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 5px;
            display: flex;
            flex-wrap: wrap;
        }

        .select-container {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Add New Book</h2>

                <!-- Book Cataloging Form -->
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Book</h4>
                    </div>
                    <div class="card-body">
                        <form id="bookCatalogForm" enctype="multipart/form-data" method="POST"
                            action="admin-book-cataloging.php">
                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Title:</label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>

                            <!-- Author -->
                            <div class="mb-3">
                                <label for="author" class="form-label">Author:</label>
                                <input type="text" id="author" name="author" class="form-control" required>
                            </div>

                            <!-- Genre -->
                            <div class="select-container mb-3">
                                <label for="genres" class="form-label">Genres:</label>
                                <select id="genres" class="form-select">
                                    <option value="">Select a genre</option>
                                    <?php
                                    // Populate genres from the database
                                    while ($row = mysqli_fetch_assoc($genresResult)) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                                <div id="selectedGenres" class="tag-container mt-2"></div>
                                <input type="hidden" name="genres[]" id="hiddenGenres">
                            </div>

                            <!-- ISBN -->
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN:</label>
                                <input type="text" id="isbn" name="isbn" class="form-control" required>
                            </div>

                            <!-- Publication Date -->
                            <div class="mb-3">
                                <label for="pubDate" class="form-label">Publication Date:</label>
                                <input type="date" id="pubDate" name="pubDate" class="form-control" required>
                            </div>

                            <!-- Language -->
                            <div class="mb-3">
                                <label for="language" class="form-label">Language:</label>
                                <input type="text" id="language" name="language" class="form-control" required>
                            </div>

                            <!-- Book Cover Image -->
                            <div class="mb-3">
                                <label for="bookCover" class="form-label">Book Cover:</label>
                                <input type="file" id="bookCover" name="bookCover" class="form-control" required
                                    accept="image/*">
                            </div>

                            <button type="submit" class="btn btn-primary">Add Book</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const genresSelect = document.getElementById('genres');
        const selectedGenresContainer = document.getElementById('selectedGenres');
        const hiddenGenresInput = document.getElementById('hiddenGenres');

        // Event listener for genre selection
        genresSelect.addEventListener('change', function () {
            const genreId = this.value;
            const genreName = this.options[this.selectedIndex].text;

            if (genreId) {
                // Check if the genre is already added
                const existingTags = Array.from(selectedGenresContainer.children).map(tag => tag.dataset.id);
                if (!existingTags.includes(genreId)) {
                    // Create a tag
                    const tag = document.createElement('span');
                    tag.className = 'tag';
                    tag.textContent = genreName;
                    tag.dataset.id = genreId;

                    const removeButton = document.createElement('span');
                    removeButton.className = 'remove-tag';
                    removeButton.textContent = '✖';
                    removeButton.onclick = function () {
                        tag.remove(); // Remove the tag
                        updateHiddenGenres(); // Update hidden input
                    };

                    tag.appendChild(removeButton);
                    selectedGenresContainer.appendChild(tag);
                    updateHiddenGenres(); // Update hidden input
                } else {
                    alert("This genre is already added.");
                }
                genresSelect.selectedIndex = 0; // Reset dropdown after selection
            } else {
                alert("Please select a genre.");
            }
        });

        function updateHiddenGenres() {
            const selectedTags = Array.from(selectedGenresContainer.children);
            const selectedGenreIds = selectedTags.map(tag => tag.dataset.id);
            hiddenGenresInput.value = selectedGenreIds.join(','); // Join selected genre IDs
        }
    </script>

    <?php include 'footer.php'; ?>
</body>

</html>