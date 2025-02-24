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
    <title>User Dashboard - Online Book Exchange</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #343a40;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        nav {
            background-color: #007bff;
            padding: 10px 0;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 15px;
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
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center the cards */
            gap: 20px;
        }
        .card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 45%; /* Set width to make two cards per row */
            box-sizing: border-box;
            margin-bottom: 20px;
            text-align: center;
        }
        .card a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            display: block;
            margin-top: 15px;
            transition: color 0.3s;
        }
        .card a:hover {
            color: #0056b3;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: auto;
        }
        .guide {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .guide h2 {
            margin-top: 0;
        }
        .guide ul {
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="book_list.php">List a Book</a></li>
            <li><a href="search_books.php">Search Books</a></li>            
            <li><a href="send_request.php">Exchange Requests</a></li>
            <li><a href="view_requests.php">View Requests</a></li>
            <li><a href="view_exchange_progress.php">View Exchange Progress</a></li>
            <li><a href="chat.php">Messages</a></li>
            <li><a href="feedback_form.php">Rate & Review</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Dashboard Section -->
    <main>
        <div class="card-container">
            <!-- Card for 'List a Book for Exchange' -->
            <div class="card">
                <h3>List a Book for Exchange</h3>
                <a href="book_list.php">Go to List Book</a>
            </div>

            <!-- Card for 'View Exchange Requests' -->
            <div class="card">
                <h3>View Exchange Requests</h3>
                <a href="view_requests.php">Go to Exchange Requests</a>
            </div>

            <!-- Card for 'Collection Manage Books' -->
            <div class="card">
                <h3>Collection Manage Books</h3>
                <a href="collection_manage_books.php">Go to Collection Manage Books</a>
            </div>

            <!-- Card for 'Check Messages' -->
            <div class="card">
                <h3>Check Messages</h3>
                <a href="chat.php">Go to Messages</a>
            </div>

            <!-- Card for 'Rate & Review Books' -->
            <div class="card">
                <h3>Rate & Review Books</h3>
                <a href="feedback_form.php">Go to Rate & Review</a>
            </div>
        </div>

        <!-- User Guide Section -->
        <div class="guide">
            <h2>Online Book Exchange Platform Guide</h2>
            <p>Welcome to the Online Book Exchange platform! Below is a brief guide to help you get started:</p>
            <ul>
                <li><strong>User registration and login:</strong> Make sure you are logged in to access the platform's features.</li>
                <li><strong>List a Book:</strong> You can list books you wish to exchange. Provide details like title, author, genre, and condition.</li>
                <li><strong>Exchange Requests:</strong> Browse available books and request exchanges with other users. You can also accept or decline requests.</li>
                <li><strong>Messaging System:</strong> Use the messaging system to communicate with users about exchange details.</li>
                <li><strong>Rate & Review:</strong> After completing an exchange, you can rate and review books and exchange partners.</li>
            </ul>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Online Book Exchange Platform</p>
    </footer>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
