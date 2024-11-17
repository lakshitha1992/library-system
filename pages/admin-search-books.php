<?php include 'navi.php'; ?>

<?php
// Include your database connection
include '../includes/db_connection.php';

// Get the query from the request
$query = isset($_GET['query']) ? $_GET['query'] : '';

if ($query) {
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT title FROM books WHERE title LIKE ? LIMIT 10");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];

    // Fetch the suggestions
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row; // Store the result in an array
    }

    // Return the suggestions as JSON
    header('Content-Type: application/json');
    echo json_encode($suggestions);

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin-registration.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Search Books</h2>

        <!-- Search Form -->
        <form id="searchForm" method="POST" action="">
            <div class="mb-3">
                <input type="text" class="form-control" id="searchQuery" name="searchQuery"
                    placeholder="Search by title, author, ISBN, or genre" required autocomplete="off">
                <div id="suggestions" class="suggestions-list"></div> <!-- Suggestions dropdown -->
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Search Results -->
        <div class="mt-4">
            <h4>Search Results:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Genre</th>
                        <th>Publication Date</th>
                        <th>Cover</th>
                        <th>Language</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if the form has been submitted
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $searchQuery = $_POST['searchQuery'];

                        // Database connection
                        include '../includes/db_connection.php';

                        // Prepared statement for search
                        $stmt = $conn->prepare("SELECT books.*, 
                                                    GROUP_CONCAT(DISTINCT categories.name SEPARATOR ', ') AS genre 
                                                 FROM books 
                                                 LEFT JOIN book_genres ON books.id = book_genres.book_id 
                                                 LEFT JOIN categories ON book_genres.genre_id = categories.id 
                                                 WHERE title LIKE ? OR author LIKE ? OR isbn LIKE ?
                                                 GROUP BY books.id");
                        $likeQuery = "%" . $searchQuery . "%";
                        $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Check if any books were found
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['title']}</td>
                                        <td>{$row['author']}</td>
                                        <td>{$row['isbn']}</td>
                                        <td>{$row['genre']}</td>
                                        <td>{$row['publication_date']}</td>
                                        <td>";
                                if (!empty($row['cover_image'])) {
                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['cover_image']) . '" alt="Cover Image" style="width: 50px; height: auto;">';
                                } else {
                                    echo 'No Image';
                                }
                                echo "</td>
                                        <td>{$row['language']}</td>
                                        <td>{$row['status']}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center alert alert-warning'>No results found.</td></tr>";
                        }

                        // Close the prepared statement and database connection
                        $stmt->close();
                        $conn->close();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript for auto-complete -->
    <script>
        const searchQuery = document.getElementById('searchQuery');
        const suggestionsContainer = document.getElementById('suggestions');

        searchQuery.addEventListener('input', function () {
            const query = this.value;

            // Clear previous suggestions
            suggestionsContainer.innerHTML = '';

            if (query.length > 2) { // Start searching after 3 characters
                fetch(`admin-search-books-fetch_suggestions.php?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate suggestions
                        data.forEach(book => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.textContent = book.title;
                            suggestionItem.className = 'suggestion-item'; // Add class for styling
                            suggestionItem.addEventListener('click', function () {
                                searchQuery.value = book.title; // Set input value to the selected suggestion
                                suggestionsContainer.innerHTML = ''; // Clear suggestions
                            });
                            suggestionsContainer.appendChild(suggestionItem);
                        });
                    })
                    .catch(error => console.error('Error fetching suggestions:', error));
            }
        });
    </script>

    <style>
        .suggestions-list {
            border: 1px solid #ccc;
            border-radius: 5px;
            position: absolute;
            /* Position it below the input */
            z-index: 1000;
            background-color: white;
            width: 100%;
            /* Make it the same width as input */
            max-height: 150px;
            /* Limit height */
            overflow-y: auto;
            /* Scroll if too many suggestions */
            display: none;
            /* Hidden by default */
        }

        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
            /* Highlight on hover */
        }
    </style>

    <?php include 'footer.php'; ?>
</body>

</html>