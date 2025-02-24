<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the book details to update
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Get the book data from the database
    $query = "SELECT * FROM books WHERE id='$book_id' AND user_id='$user_id'";
    $result = mysqli_query($conn, $query);
    
    // Check if the book exists and belongs to the logged-in user
    if (mysqli_num_rows($result) == 0) {
        // Book not found or does not belong to the user
        header('Location: book_list.php');
        exit();
    }

    // Fetch the book data
    $book = mysqli_fetch_assoc($result);
} else {
    // No book_id passed, redirect to book list page
    header('Location: book_list.php');
    exit();
}

// Update book details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $condition = $_POST['condition'];

    // Update the book in the database
    $query = "UPDATE books SET title='$title', author='$author', genre='$genre', `condition`='$condition' 
              WHERE id='$book_id' AND user_id='$user_id'";
    mysqli_query($conn, $query);

    // Redirect back to book list page
    header('Location: book_list.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Update Book Details</h1>

<!-- Update Book Form -->
<form action="update_book.php?book_id=<?php echo $book_id; ?>" method="POST">
    <input type="text" name="title" value="<?php echo $book['title']; ?>" placeholder="Book Title" required>
    <input type="text" name="author" value="<?php echo $book['author']; ?>" placeholder="Author" required>
    <input type="text" name="genre" value="<?php echo $book['genre']; ?>" placeholder="Genre" required>
    <select name="condition" required>
        <option value="new" <?php if ($book['condition'] == 'new') echo 'selected'; ?>>New</option>
        <option value="good" <?php if ($book['condition'] == 'good') echo 'selected'; ?>>Good</option>
        <option value="fair" <?php if ($book['condition'] == 'fair') echo 'selected'; ?>>Fair</option>
        <option value="poor" <?php if ($book['condition'] == 'poor') echo 'selected'; ?>>Poor</option>
    </select>
    <button type="submit" name="update_book">Update Book</button>
</form>

</body>
</html>
