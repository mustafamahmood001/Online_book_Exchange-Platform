<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection file (update the path as needed)
include('../config/config.php');  // Update the path if necessary

// Fetch all books (for admin view)
$query = "SELECT b.id, b.title, b.author, b.genre, b.status, u.name 
          FROM books b 
          JOIN users u ON b.user_id = u.id";
$result = mysqli_query($conn, $query);

// Approve or reject a book listing
if (isset($_GET['approve_book'])) {
    $book_id = $_GET['approve_book'];
    $query = "UPDATE books SET status = 'available' WHERE id = '$book_id'";
    mysqli_query($conn, $query);
    header('Location: admin_books.php');
    exit();
}

if (isset($_GET['reject_book'])) {
    $book_id = $_GET['reject_book'];
    $query = "UPDATE books SET status = 'removed' WHERE id = '$book_id'";
    mysqli_query($conn, $query);
    header('Location: admin_books.php');
    exit();
}

if (isset($_GET['delete_book'])) {
    $book_id = $_GET['delete_book'];
    $query = "DELETE FROM books WHERE id = '$book_id'";
    mysqli_query($conn, $query);
    header('Location: admin_books.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 5px 10px;
            margin: 5px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button.reject {
            background-color: #DC3545;
        }
        button.delete {
            background-color: #6C757D;
        }
        button:hover {
            opacity: 0.9;
        }
    </style>
    <script>
        // Confirm before approving/rejecting/deleting a book
        function confirmAction(action, bookId) {
            let message = '';
            if (action === 'approve') {
                message = 'Are you sure you want to approve this book?';
            } else if (action === 'reject') {
                message = 'Are you sure you want to reject this book?';
            } else if (action === 'delete') {
                message = 'Are you sure you want to delete this book?';
            }
            if (confirm(message)) {
                window.location.href = 'admin_books.php?' + action + '_book=' + bookId;
            }
        }
    </script>
</head>
<body>
<h1>Manage Users' Books</h1>

<table>
    <tr>
        <th>Book Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Status</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php while ($book = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($book['title']); ?></td>
            <td><?php echo htmlspecialchars($book['author']); ?></td>
            <td><?php echo htmlspecialchars($book['genre']); ?></td>
            <td><?php echo htmlspecialchars($book['status']); ?></td>
            <td><?php echo htmlspecialchars($book['name']); ?></td>
            <td>
                <?php if ($book['status'] == 'pending') { ?>
                    <button onclick="confirmAction('approve', <?php echo $book['id']; ?>)">Approve</button>
                    <button class="reject" onclick="confirmAction('reject', <?php echo $book['id']; ?>)">Reject</button>
                <?php } ?>
                <button class="delete" onclick="confirmAction('delete', <?php echo $book['id']; ?>)">Delete</button>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
