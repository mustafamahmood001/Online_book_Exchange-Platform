<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $user_id = $_SESSION['user_id']; // Assuming the user's ID is stored in the session
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $condition = mysqli_real_escape_string($conn, $_POST['condition']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // Insert into the database
    $query = "INSERT INTO books (user_id, title, author, genre, `condition`, description, location, status) 
              VALUES ('$user_id', '$title', '$author', '$genre', '$condition', '$description', '$location', 'pending')";

    if (mysqli_query($conn, $query)) {
        // Redirect back to book list page with success message
        $_SESSION['message'] = "Book added successfully! Awaiting admin approval.";
        header('Location: book_list.php');
        exit();
    } else {
        // Handle database error
        $_SESSION['error'] = "Error adding book: " . mysqli_error($conn);
        header('Location: book_list.php');
        exit();
    }
} else {
    // Redirect to book list if accessed directly
    header('Location: book_list.php');
    exit();
}
?>
