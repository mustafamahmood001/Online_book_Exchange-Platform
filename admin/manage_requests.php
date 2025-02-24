<?php
session_start();

// Ensure the user is logged in as admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

include('../config/config.php');

// Fetch all book requests from the database, including description, location, genre, and condition
$query = "SELECT br.id, u.name, b.title, b.description, b.location, b.genre, b.condition, br.status, br.request_date 
          FROM book_requests br 
          JOIN users u ON br.user_id = u.id 
          JOIN books b ON br.book_id = b.id";
$result = mysqli_query($conn, $query);

// Update request status (Accept or Reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_request'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE book_requests SET status='$status' WHERE id='$request_id'";
    if (mysqli_query($conn, $update_query)) {
        header('Location: manage_requests.php');
        exit();
    } else {
        echo "Error updating request: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Book Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            font-size: 28px;
            color: #333;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        select {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        select:focus {
            border-color: #007bff;
        }

        button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        form {
            margin: 0;
            display: inline-block;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                width: 90%;
            }

            h1 {
                font-size: 24px;
            }

            select, button {
                font-size: 12px;
                padding: 6px;
            }
        }
    </style>
</head>
<body>

<h1>Manage Book Requests</h1>

<table>
    <tr>
        <th>Name</th>
        <th>Book Title</th>
        <th>Genre</th> <!-- New column for Genre -->
        <th>Condition</th> <!-- New column for Condition -->
        <th>Description</th>
        <th>Location</th>
        <th>Status</th>
        <th>Request Date</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['genre']); ?></td> <!-- Display Genre -->
            <td><?php echo htmlspecialchars($row['condition']); ?></td> <!-- Display Condition -->
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['request_date']); ?></td>
            <td>
                <form action="manage_requests.php" method="POST">
                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                    <select name="status" required>
                        <option value="pending" <?php echo ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="accepted" <?php echo ($row['status'] == 'accepted') ? 'selected' : ''; ?>>Accepted</option>
                        <option value="rejected" <?php echo ($row['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                    <button type="submit" name="update_request">Update Status</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<a href="dashboard.php" class="back-btn">Back to Dashboard</a>

</body>
</html>
