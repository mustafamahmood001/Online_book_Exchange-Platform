<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../config/config.php');
$user_id = $_SESSION['user_id'];

// Fetch all book exchange statuses along with additional book details for the logged-in user
$query = "SELECT br.id, b.title, b.author, b.genre, b.condition, b.description, b.location, et.status, et.updated_at 
          FROM book_requests br 
          JOIN books b ON br.book_id = b.id
          LEFT JOIN book_exchange_tracking et ON br.id = et.book_request_id
          WHERE br.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track My Exchange Requests</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
            background-color: #e9e9e9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        /* Back Button Styling */
        .back-btn {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #0056b3;
            transition: background-color 0.3s;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            table {
                width: 100%;
            }

            th, td {
                font-size: 14px;
                padding: 10px;
            }

            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<h1>Track My Exchange Requests</h1>

<table>
    <tr>
        <th>Book Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Condition</th>
        <th>Description</th>
        <th>Location</th>
        <th>Status</th>
        <th>Last Update</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td><?php echo htmlspecialchars($row['genre']); ?></td>
            <td><?php echo htmlspecialchars($row['condition']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
        </tr>
    <?php } ?>
</table>

<a href="dashboard.php" class="back-btn">Back to Dashboard</a>

</body>
</html>
