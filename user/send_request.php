<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle book request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_request'])) {
    $book_id = $_POST['book_id'];

    // Insert request into the database
    $query = "INSERT INTO book_requests (user_id, book_id, status) VALUES ('$user_id', '$book_id', 'pending')";
    if (mysqli_query($conn, $query)) {
        echo "Request Sent Successfully!";
    } else {
        echo "Error sending request: " . mysqli_error($conn);
    }
}

// Fetch books to be requested (you can customize this query)
$query = "SELECT * FROM books WHERE status = 'approved'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Request</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #555;
            margin-bottom: 30px;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            form {
                width: 90%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<h1>Request a Book</h1>

<form action="send_request.php" method="POST">
    <label for="book">Choose a Book:</label>
    <select name="book_id" id="book" required>
        <?php while ($book = mysqli_fetch_assoc($result)) { ?>
            <option value="<?php echo $book['id']; ?>"><?php echo $book['title']; ?> by <?php echo $book['author']; ?></option>
        <?php } ?>
    </select>
    <button type="submit" name="send_request">Send Request</button>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</form>

</body>
</html>
