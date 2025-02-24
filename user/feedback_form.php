<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in
    $feedback_message = mysqli_real_escape_string($conn, $_POST['feedback_message']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);

    // Insert feedback into the database
    $query = "INSERT INTO feedback (book_id, user_id, feedback_message, rating) 
              VALUES ('$book_id', '$user_id', '$feedback_message', '$rating')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Feedback submitted successfully!";
    } else {
        $_SESSION['error'] = "Error submitting feedback: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
            background-color: #4CAF50;
            padding: 20px;
            color: white;
            width: 100%;
            margin: 0;
        }

        .container {
            background-color: #fff;
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
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

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }

        select, textarea, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            padding: 12px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submit Feedback for Book</h1>

        <!-- Display success or error messages -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='message success'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['error'])) {
            echo "<div class='message error'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <!-- Feedback Form -->
        <form method="POST" action="">
            <label for="book_id">Select Book:</label>
            <select name="book_id" id="book_id" required>
                <!-- Populate with available books -->
                <?php
                $query_books = "SELECT id, title FROM books";
                $result_books = mysqli_query($conn, $query_books);
                while ($row = mysqli_fetch_assoc($result_books)) {
                    echo "<option value='{$row['id']}'>{$row['title']}</option>";
                }
                ?>
            </select><br>

            <label for="feedback_message">Feedback:</label><br>
            <textarea name="feedback_message" id="feedback_message" rows="4" required></textarea><br>

            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select><br><br>

            <button type="submit" name="submit_feedback">Submit Feedback</button>
        </form>

        <a href="book_list.php" class="back-btn">Back to Book List</a>
    </div>
</body>
</html>
