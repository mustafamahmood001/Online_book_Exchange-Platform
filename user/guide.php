<?php
session_start();
include('../config/config.php');

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Fetch user information
$email = $_SESSION['user'];
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Guide - Online Book Exchange</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        header {
            background-color: #343a40;
            color: white;
            padding: 20px 30px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        nav {
            background-color: #007bff;
            padding: 15px 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        main {
            flex-grow: 1;
            padding: 30px;
            max-width: 1100px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .guide-section {
            margin-bottom: 30px;
        }

        .guide-section h2 {
            font-size: 28px;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .guide-section p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555;
        }

        .guide-section ul {
            margin-top: 10px;
            padding-left: 20px;
            list-style-type: disc;
        }

        .guide-section ul li {
            font-size: 16px;
            color: #555;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        .back-to-dashboard {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }

        .back-to-dashboard:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <header>
        <h1>User Guide - Online Book Exchange</h1>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="book_list.php">List a Book</a></li>
            <li><a href="search_books.php">Search Books</a></li>
            <li><a href="send_request.php">Exchange Requests</a></li>
            <li><a href="view_requests.php">View Requests</a></li>
            <li><a href="chat.php">Messages</a></li>
            <li><a href="view_exchange_progress.php">View Exchange Progress</a></li>
            <li><a href="feedback_form.php">Rate & Review</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content Section -->
    <main>
        <div class="guide-section">
            <h2>Welcome to the User Guide</h2>
            <p>This guide will help you navigate the features of the Online Book Exchange platform. Below you will find
                instructions for using each of the key functionalities.</p>
        </div>

        <div class="guide-section">
            <h2>How to List a Book</h2>
            <p>To list a book on the platform, follow these steps:</p>
            <ul>
                <li>Click on "List a Book" from the main navigation menu.</li>
                <li>Fill in the necessary details about the book (title, author, genre, etc.) in the form provided.</li>
                <li>Click on "Submit" to add the book to the platform.</li>
                <li>Your book will now be available for others to view and request for exchange.</li>
            </ul>
        </div>

        <div class="guide-section">
            <h2>How to Search for Books</h2>
            <p>To find books available for exchange:</p>
            <ul>
                <li>Go to the "Search Books" section.</li>
                <li>Use the search bar to find books by title, author, or genre.</li>
                <li>Click on a book to view more details and initiate an exchange request.</li>
            </ul>
        </div>

        <div class="guide-section">
            <h2>How to Request an Exchange</h2>
            <p>Follow these steps to request an exchange for a book:</p>
            <ul>
                <li>Go to the "Send Request" section and select a book you want to exchange.</li>
                <li>Fill out the required details for your exchange request.</li>
                <li>Submit the request, and the book owner will be notified of your interest.</li>
            </ul>
        </div>

        <div class="guide-section">
            <h2>How to View Your Requests</h2>
            <p>To see the status of your exchange requests:</p>
            <ul>
                <li>Navigate to the "View Requests" section.</li>
                <li>Here, you can track the status of your ongoing exchange requests and their current progress.</li>
            </ul>
        </div>

        <div class="guide-section">
            <h2>How to Send Messages</h2>
            <p>You can communicate directly with other users:</p>
            <ul>
                <li>Go to the "Messages" section to send and receive messages.</li>
                <li>Click on a user to start a new conversation.</li>
                <li>Send messages to discuss exchange details or book availability.</li>
            </ul>
        </div>

        <div class="guide-section">
            <h2>How to View Your Exchange Progress</h2>
            <p>Track your exchange requests in the "View Exchange Progress" section:</p>
            <ul>
                <li>Here, you can see the progress of each request and its current stage (pending, accepted, completed).</li>
            </ul>
        </div>

        <a href="dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Online Book Exchange Platform. All rights reserved.</p>
    </footer>

</body>

</html>
