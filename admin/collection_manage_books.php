<?php
session_start();

// Ensure the user is logged in as admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

include('../config/config.php');

// Delete Book from Collection
if (isset($_GET['delete_id'])) {
    $book_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $query = "DELETE FROM book_collection WHERE id = '$book_id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Book deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting book: " . mysqli_error($conn);
    }
}

// Approve Book (change status)
if (isset($_GET['approve_id'])) {
    $book_id = mysqli_real_escape_string($conn, $_GET['approve_id']);
    $query = "UPDATE book_collection SET status = 'approved' WHERE id = '$book_id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Book approved successfully!";
    } else {
        $_SESSION['error'] = "Error approving book: " . mysqli_error($conn);
    }
}

// Reject Book (change status)
if (isset($_GET['reject_id'])) {
    $book_id = mysqli_real_escape_string($conn, $_GET['reject_id']);
    $query = "UPDATE book_collection SET status = 'rejected' WHERE id = '$book_id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Book rejected successfully!";
    } else {
        $_SESSION['error'] = "Error rejecting book: " . mysqli_error($conn);
    }
}

// Fetch all books with user names (from all users)
$query = "
    SELECT bc.*, u.name 
    FROM book_collection bc 
    LEFT JOIN users u ON bc.user_id = u.id
";
$result = mysqli_query($conn, $query);

// Check for errors
if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Book Collection</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }

    h1, h3 {
        text-align: center;
        margin-top: 30px;
        font-size: 28px;
        color: #333;
    }

    p {
        text-align: center;
        font-size: 16px;
        margin: 10px;
    }

    table {
        width: 80%;
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

    a {
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
    }

    a:hover {
        text-decoration: underline;
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
        margin-top: 20px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    button:hover {
        background-color: #0056b3;
    }

    /* Success/Message alert styles */
    .success, .error {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin: 20px;
        padding: 10px;
        border-radius: 4px;
    }

    .success {
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

        h1, h3 {
            font-size: 24px;
        }

        p {
            font-size: 14px;
        }

        button {
            font-size: 12px;
            padding: 6px;
        }
    }
</style>

</head>
<body>

<h1>Admin - Manage Book Collection</h1>

<!-- Display success or error messages -->
<?php
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

<h3>All Users' Book Collections</h3>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Book Title</th>
        <th>Status</th>
        <th>Added At</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['added_at']); ?></td>
            <td>
                <!-- Approve Book -->
                <?php if ($row['status'] != 'approved' && $row['status'] != 'rejected') { ?>
                    <a href="collection_manage_books.php?approve_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to approve this book?');">Approve</a> |
                <?php } else { ?>
                    <span>Approved</span> |
                <?php } ?>

                <!-- Reject Book -->
                <?php if ($row['status'] != 'rejected' && $row['status'] != 'approved') { ?>
                    <a href="collection_manage_books.php?reject_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to reject this book?');">Reject</a> |
                <?php } else { ?>
                    <span>Rejected</span> |
                <?php } ?>

                <!-- Delete Book -->
                <a href="collection_manage_books.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
<a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</body>
</html>
