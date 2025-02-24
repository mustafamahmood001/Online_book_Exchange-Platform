<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and font settings */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            line-height: 1.6;
        }

        /* Header styles */
        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 1.8em;
            font-weight: bold;
        }

        /* Main container for page content */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Navigation bar */
        nav {
            background-color: #34495e;
            padding: 15px;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #ecf0f1;
            font-size: 1.2em;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #3498db;
        }

        /* Footer styles */
        footer {
            background-color: #2c3e50;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        /* Button styling */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #2ecc71;
            color: white;
            font-size: 1.1em;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #27ae60;
        }

        /* Section headings */
        h2 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
        }

        /* Link styling for manage sections */
        .manage-link {
            color: #3498db;
            text-decoration: none;
            font-size: 1.2em;
            margin-bottom: 10px;
            display: inline-block;
        }

        .manage-link:hover {
            text-decoration: underline;
        }

/* Card styles */
.card {
    background-color: #ecf0f1;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
}

.card h3 {
    font-size: 1.4em;
    color: #333;
    margin-bottom: 15px; /* Added margin for spacing */
}

.card a {
    text-decoration: none;
    background-color: #3498db;
    color: white;
    padding: 12px 24px;
    border-radius: 5px;
    font-size: 1.1em;
    transition: background-color 0.3s ease;
    display: inline-block;
    margin-top: 10px;
    font-weight: bold; /* Added bold to make the link stand out */
}

.card a:hover {
    background-color: #2980b9;
}

/* Card Container: Grid Layout */
.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

/* Adjust card size on smaller screens */
@media (max-width: 768px) {
    .card-container {
        grid-template-columns: 1fr 1fr; /* Two columns for smaller screens */
    }
}
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        Admin Dashboard
    </header>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="../common/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content Section -->
    <div class="container">
        <div class="main-content">
            <h2>Welcome, Admin!</h2>
            <p>You can manage the platform settings, users, books, and more from this dashboard. Choose one of the options below to begin:</p>

            <!-- Card Container -->
            <div class="card-container">
                <!-- Card for 'Manage Users' -->
                <div class="card">
                    <h3>Manage Users</h3>
                    <a href="manage_users.php">Go to Manage Users</a>
                </div>

                <!-- Card for 'Manage Books' -->
                <div class="card">
                    <h3>Manage Books</h3>
                    <a href="manage_books.php">Go to Manage Books</a>
                </div>

                <!-- Card for 'Manage Exchange Requests' -->
                <div class="card">
                    <h3>Manage Exchange Requests</h3>
                    <a href="manage_requests.php">Go to Manage Requests</a>
                </div>

                <!-- Card for 'Track and Update' -->
                <div class="card">
                    <h3>Track and Update</h3>
                    <a href="manage_exchange_tracking.php">Go to Track and Update</a>
                </div>

                <!-- Card for 'Collection Manage Books' -->
                <div class="card">
                    <h3>Collection Manage Books</h3>
                    <a href="collection_manage_books.php">Go to Collection Manage Books</a>
                </div>

                <!-- Card for 'Manage Reviews' -->
                <div class="card">
                    <h3>Manage Book Feedback</h3>
                    <a href="manage_feedback.php">Go to Feedback</a>
                </div>

                <!-- Card for 'View User Query' -->
                <div class="card">
                    <h3>View User Query</h3>
                    <a href="chat.php">Go to User Query</a>
                </div>
            </div>

            <!-- Additional button if needed -->
            <a href="../common/logout.php" class="btn">Logout</a>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; 2024 Online Book Exchange Platform. All Rights Reserved.
    </footer>

</body>
</html>
