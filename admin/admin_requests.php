<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection file (update the path as needed)
include('../config/config.php');  // Ensure the path is correct

// Query to fetch all book requests
$query = "SELECT r.id, r.user_id, r.book_id, r.status, b.title as book_title, u.username as user_name
          FROM book_requests r
          JOIN books b ON r.book_id = b.id
          JOIN users u ON r.user_id = u.id";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Book Requests</title>
</head>
<body>

<h1>Manage Book Requests</h1>

<h2>All Requests</h2>
<table>
    <thead>
        <tr>
            <th>Book Title</th>
            <th>User</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['book_title']; ?></td>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <?php if ($row['status'] == 'pending') { ?>
                        <a href="update_request.php?action=accept&id=<?php echo $row['id']; ?>">Accept</a> |
                        <a href="update_request.php?action=reject&id=<?php echo $row['id']; ?>">Reject</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
