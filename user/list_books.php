<?php
session_start();
include('../config/config.php');

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Initialize SQL query to fetch all books
$query = "SELECT * FROM books";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Search functionality
if (isset($_POST['search'])) {
    $search_term = trim($_POST['search_term']);
    if (!empty($search_term)) {
        $search_query = "SELECT * FROM books WHERE (title LIKE ? OR author LIKE ? OR genre LIKE ?)";
        $stmt = $conn->prepare($search_query);
        $search_term_wildcard = "%" . $search_term . "%";
        $stmt->bind_param("sss", $search_term_wildcard, $search_term_wildcard, $search_term_wildcard);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books - Online Book Exchange</title>
    <style>
        /* Basic styles for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #27ae60;
        }

        .book-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .book-item {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
        }

        .book-item h3 {
            font-size: 1.2em;
            color: #333;
        }

        .book-item p {
            font-size: 1em;
            color: #666;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .no-results {
            text-align: center;
            color: #555;
            margin-top: 20px;
            font-size: 1.2em;
        }

        /* Back button styling */
        .back-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
            padding: 10px 20px;
            background-color: #f39c12;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Books for Exchange</h1>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="POST" action="list_books.php">
                <input type="text" name="search_term" placeholder="Search by title, author, or genre" value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>" required>
                <button type="submit" name="search">Search</button>
            </form>
        </div>

        <!-- Book List -->
        <div class="book-list">
            <?php
            if ($result->num_rows > 0) {
                while ($book = $result->fetch_assoc()) {
                    echo "<div class='book-item'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p><strong>Author:</strong> " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p><strong>Genre:</strong> " . htmlspecialchars($book['genre']) . "</p>";
                    echo "<p><strong>Condition:</strong> " . htmlspecialchars($book['condition']) . "</p>";
                    echo "<p><strong>Description:</strong> " . htmlspecialchars($book['description']) . "</p>";
                    echo "<a href='request_exchange.php?book_id=" . $book['id'] . "' class='btn'>Request Exchange</a>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-results'>No books available at the moment.</p>";
            }
            ?>
        </div>
    </div>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>  <!-- Back to Dashboard Button -->

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
