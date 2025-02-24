<?php
session_start();
include('../config/config.php');

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Delete feedback functionality
if (isset($_GET['delete_feedback'])) {
    $feedback_id = mysqli_real_escape_string($conn, $_GET['delete_feedback']);

    // Check if the feedback exists
    $check_query = "SELECT * FROM feedback WHERE id = '$feedback_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Delete feedback from the database
        $delete_query = "DELETE FROM feedback WHERE id = '$feedback_id'";
        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['message'] = "Feedback deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting feedback: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Feedback not found!";
    }
}

// Fetch all feedbacks from the database
$query = "SELECT f.id, f.feedback_message, f.rating, f.created_at, u.name AS user_name, b.title AS book_title
          FROM feedback f
          JOIN users u ON f.user_id = u.id
          JOIN books b ON f.book_id = b.id
          ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-top: 30px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td a {
            color: #f44336;
            text-decoration: none;
            font-weight: bold;
        }

        td a:hover {
            color: #d32f2f;
        }

        .message {
            width: 80%;
            margin: 20px auto;
            padding: 10px;
            background-color: #f4f4f4;
            border-left: 5px solid #4CAF50;
            font-size: 1.1em;
        }

        .error {
            border-left: 5px solid #f44336;
            background-color: #fff3f3;
            color: #f44336;
        }

        .back-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            text-align: center;
        }

        .back-button:hover {
            background-color: #45a049;
        }

        @media screen and (max-width: 768px) {
            table {
                width: 100%;
            }
            th, td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <h1>Manage Book Feedback</h1>

    <!-- Display success or error messages -->
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }

    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <!-- Table to display feedback -->
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Feedback ID</th>
                <th>User</th>
                <th>Book Title</th>
                <th>Feedback Message</th>
                <th>Rating</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['feedback_message'])); ?></td>
                    <td><?php echo htmlspecialchars($row['rating']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <!-- Delete link with confirmation -->
                        <a href="manage_feedback.php?delete_feedback=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Back to Admin Dashboard -->
    <br><br>
    <a href="dashboard.php" class="back-button">Back to Dashboard</a>
</body>
</html>
