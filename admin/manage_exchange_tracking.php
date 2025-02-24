<?php
session_start();

// Ensure the user is logged in as admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

include('../config/config.php');

// Fetch all book exchange requests for the admin, including Author, Genre, Condition, Description, and Location
$query = "SELECT br.id as book_request_id, u.name, b.title, b.author, b.genre, b.condition, b.description, b.location,
          IFNULL(et.status, 'pending') as status, 
          IFNULL(et.updated_at, 'Not updated yet') as updated_at 
          FROM book_requests br 
          JOIN users u ON br.user_id = u.id 
          JOIN books b ON br.book_id = b.id
          LEFT JOIN book_exchange_tracking et ON br.id = et.book_request_id";

$result = mysqli_query($conn, $query);

// Check for errors in the query
if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}

// Update exchange status (Admin updates stages: Pending -> Pickup -> Delivery -> Completed)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_exchange_status'])) {
    $exchange_id = $_POST['exchange_id'];  // The ID of the exchange
    $new_status = $_POST['status'];  // The new status value from the dropdown

    // Ensure the status is not empty or invalid
    if (empty($new_status)) {
        $_SESSION['error'] = "Status is required!";
        header('Location: manage_exchange_tracking.php');
        exit();
    }

    // Check if this exchange already has a status record, if not, insert one
    $check_query = "SELECT id FROM book_exchange_tracking WHERE book_request_id = '$exchange_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        // Insert new entry if no status exists
        $insert_query = "INSERT INTO book_exchange_tracking (book_request_id, status, updated_at)
                         VALUES ('$exchange_id', '$new_status', NOW())";
        mysqli_query($conn, $insert_query);
    } else {
        // Otherwise, update the existing entry
        $update_query = "UPDATE book_exchange_tracking 
                         SET status='$new_status', updated_at=NOW() 
                         WHERE book_request_id='$exchange_id'";
        mysqli_query($conn, $update_query);
    }

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['message'] = "Exchange status updated successfully!";
        header('Location: manage_exchange_tracking.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating exchange status: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Exchange Tracking</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        margin-top: 30px;
        font-size: 28px;
        color: #333;
    }

    p {
        text-align: center;
        color: green;
        font-size: 16px;
    }

    table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

    /* Success/Message alert styles */
    .success, .error {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin: 20px;
        padding: 10px;
        border-radius: 4px;
        background-color: #e0ffe0;
        color: green;
    }

    .error {
        background-color: #ffe0e0;
        color: red;
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

        p {
            font-size: 14px;
        }
    }
</style></head>
<body>

<h1>Manage Exchange Tracking</h1>

<!-- Display success or error messages -->
<?php
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo "<p>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Book Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Condition</th>
        <th>Description</th>
        <th>Location</th>
        <th>Status</th>
        <th>Last Update</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td><?php echo htmlspecialchars($row['genre']); ?></td>
            <td><?php echo htmlspecialchars($row['condition']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
            <td>
                <!-- Form to update status -->
                <form action="manage_exchange_tracking.php" method="POST">
                    <input type="hidden" name="exchange_id" value="<?php echo $row['book_request_id']; ?>">
                    <select name="status" required>
                        <option value="pending" <?php echo ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="pickup" <?php echo ($row['status'] == 'pickup') ? 'selected' : ''; ?>>Pickup</option>
                        <option value="delivery" <?php echo ($row['status'] == 'delivery') ? 'selected' : ''; ?>>Delivery</option>
                        <option value="completed" <?php echo ($row['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                    <button type="submit" name="update_exchange_status">Update Status</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</body>
</html>
