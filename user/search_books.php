<?php
session_start();
include('../config/config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$search_results = [];

// Handle search request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $location = mysqli_real_escape_string($conn, $_POST['location']); // Optional if location is tracked

    $query = "SELECT * FROM books WHERE status='approved'";

    if (!empty($title)) {
        $query .= " AND title LIKE '%$title%'";
    }
    if (!empty($author)) {
        $query .= " AND author LIKE '%$author%'";
    }
    if (!empty($genre)) {
        $query .= " AND genre LIKE '%$genre%'";
    }

    $result = mysqli_query($conn, $query);
    while ($book = mysqli_fetch_assoc($result)) {
        $search_results[] = $book;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Books</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            color: #555;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        form input {
            padding: 10px;
            margin: 5px;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Search Books</h1>

    <!-- Search Form -->
    <form action="search_books.php" method="POST">
        <input type="text" name="title" placeholder="Book Title">
        <input type="text" name="author" placeholder="Author">
        <input type="text" name="genre" placeholder="Genre">
        <input type="text" name="location" placeholder="Location">
        <button type="submit">Search</button>
    </form>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

    <!-- Display Search Results -->
    <?php if (!empty($search_results)) { ?>
    <h2>Search Results:</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Condition</th>
            <th>Location</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($search_results as $book) { ?>
        <tr>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo $book['genre']; ?></td>
            <td><?php echo $book['condition']; ?></td>
            <td><?php echo $book['location']; ?></td>
            <td><?php echo $book['description']; ?></td>
            <td>
                <a href="send_request.php?book_id=<?php echo $book['id']; ?>">Request</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php } ?>
</body>
</html>
