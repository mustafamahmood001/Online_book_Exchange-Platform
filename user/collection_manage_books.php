<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle book status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $user_id = $_SESSION['user_id'];

    // Update book status
    $query = "UPDATE book_collection SET status = '$new_status' WHERE id = '$book_id' AND user_id = '$user_id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Book status updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating status: " . mysqli_error($conn);
    }
}

// Handle book deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $user_id = $_SESSION['user_id'];

    // Delete the book from the user's collection
    $delete_query = "DELETE FROM book_collection WHERE id = '$delete_id' AND user_id = '$user_id'";

    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['message'] = "Book deleted from your collection!";
    } else {
        $_SESSION['error'] = "Error deleting book: " . mysqli_error($conn);
    }

    // Redirect to avoid resubmitting the form when refreshing the page
    header('Location: collection_manage_books.php');
    exit();
}

// Add Book to User's Collection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $user_id = $_SESSION['user_id'];
    $book_title = mysqli_real_escape_string($conn, $_POST['book_title']);
    $status = 'available'; // Default status when a new book is added

    $query = "INSERT INTO book_collection (user_id, book_title, status) VALUES ('$user_id', '$book_title', '$status')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Book added to your collection!";
    } else {
        $_SESSION['error'] = "Error adding book: " . mysqli_error($conn);
    }
}

// Fetch the user's books
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM book_collection WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

// Check for errors
if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Book Collection</title>
    <style>
/* General Styling */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 20px;
    color: #333;
}

h1, h3 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

/* Centering the form and table */
form {
    margin-bottom: 20px;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 10px auto;
}

table {
    width: 80%;
    margin: 30px auto;
    border-collapse: collapse;
    background-color: #fff;
    border: 1px solid #ddd;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

/* Actions in a single line */
td {
    text-align: center;
    padding: 18px 25px;
    border: 1px solid #ddd;
}

.actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.actions form {
    display: inline-block;
}

.actions a {
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration: none;
    color: #ff3333;
    font-weight: bold;
}

.actions a:hover {
    text-decoration: underline;
    color: #cc0000;
    background-color: #fff;
}

button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
    transition: background-color 0.3s;
}

/* Responsive Design */
@media (max-width: 768px) {
    table {
        width: 100%;
    }

    th, td {
        font-size: 14px;
        padding: 12px;
    }

    form {
        width: 90%;
    }

    button {
        width: auto;
    }
}
/* Button Styling */
button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px; /* Reduced padding for smaller size */
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px; /* Smaller font size */
    transition: background-color 0.3s ease, transform 0.2s ease;
    width: auto; /* Button width should fit the content */
    display: inline-block; /* Makes the button align properly within the form */
}

button:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: scale(1.05); /* Slightly enlarges the button on hover */
}

/* Additional Focus Styling */
button:focus {
    outline: none; /* Removes the default outline */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Subtle shadow on focus */
}

    </style>
</head>
<body>

<h1>Your Book Collection</h1>

<!-- Display success or error messages -->
<?php
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

<!-- Add Book Form -->
<h3>Add Book to Your Collection</h3>
<form action="collection_manage_books.php" method="POST">
    <label for="book_title">Book Title:</label>
    <input type="text" name="book_title" required>
    <button type="submit" name="add_book">Add Book</button>
</form>

<h3>Your Books</h3>
<table border="1">
    <tr>
        <th>Book Title</th>
        <th>Status</th>
        <th>Added At</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['added_at']); ?></td>
            <td>
                <!-- Form for updating book status -->
                <form action="collection_manage_books.php" method="POST" style="display:inline;">
                    <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                    <select name="status">
                        <option value="available" <?php echo ($row['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                        <option value="exchanged" <?php echo ($row['status'] == 'exchanged') ? 'selected' : ''; ?>>Exchanged</option>
                        <option value="pending" <?php echo ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    </select>
                    <button type="submit" name="update_status">Update Status</button>
                </form> |
                <!-- Form for deleting the book -->
                <a href="collection_manage_books.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</body>
</html>
