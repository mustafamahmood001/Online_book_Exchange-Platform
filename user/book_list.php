<?php
session_start();
include('../config/config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle book deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    
    // Step 1: Delete related requests from the book_requests table
    $delete_requests_query = "DELETE FROM book_requests WHERE book_id='$book_id'";
    mysqli_query($conn, $delete_requests_query);
    
    // Step 2: Now delete the book from the books table
    $delete_book_query = "DELETE FROM books WHERE id='$book_id' AND user_id='$user_id'";
    if (mysqli_query($conn, $delete_book_query)) {
        $_SESSION['message'] = "Book removed successfully!";
    } else {
        $_SESSION['message'] = "Error deleting book.";
    }
    header('Location: book_list.php');
    exit();
}

// Fetch user's books
$query = "SELECT * FROM books WHERE user_id='$user_id'";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book List - My Collection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            margin: 0;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form input, form select, form button, form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .message {
            max-width: 600px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Book List - My Collection</h1>

    <!-- Display success or error messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message <?php echo isset($_SESSION['error']) ? 'error' : 'success'; ?>">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Add Book Form -->
    <form action="add_book.php" method="POST">
        <label for="title">Book Title:</label>
        <input type="text" name="title" required><br>

        <label for="author">Author:</label>
        <input type="text" name="author" required><br>

        <label for="genre">Genre:</label>
        <input type="text" name="genre" required><br>

        <label for="condition">Condition:</label>
        <select name="condition" required>
            <option value="new">New</option>
            <option value="good">Good</option>
            <option value="fair">Fair</option>
            <option value="poor">Poor</option>
        </select>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="location">Location:</label>
        <input type="text" name="location" required><br>

        <input type="submit" value="Add Book">
    </form>

    <!-- Display Books -->
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Condition</th>
            <th>Description</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($book = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo $book['genre']; ?></td>
            <td><?php echo $book['condition']; ?></td>
            <td><?php echo $book['description']; ?></td>
            <td><?php echo $book['location']; ?></td>
            <td><?php echo $book['status']; ?></td>
            <td>
                <a href="book_list.php?action=delete&book_id=<?php echo $book['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</body>
</html>
