<?php
session_start();
include('../config/config.php');

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Approve, delete, or reject a book
if (isset($_GET['action']) && isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        $query = "UPDATE books SET status='approved' WHERE id='$book_id'";
    } elseif ($action === 'delete') {
        $query = "DELETE FROM books WHERE id='$book_id'";
    } elseif ($action === 'reject') {
        $query = "UPDATE books SET status='rejected' WHERE id='$book_id'";
    }

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Book $action successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header('Location: manage_books.php');
    exit();
}

// Fetch all books including description and location
$query = "SELECT * FROM books";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Books</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px 18px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        table td {
            background-color: #f9f9f9;
        }

        table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        /* Action Links */
        a {
            padding: 8px 12px;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            background-color: #28a745;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        a:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        a:active {
            background-color: #1e7e34;
        }

        /* Reject Button Styling */
        a.reject {
            background-color: #dc3545; /* Red color for 'Reject' button */
        }

        a.reject:hover {
            background-color: #c82333; /* Darker red on hover */
        }

        a.reject:active {
            background-color: #bd2130; /* Even darker red when clicked */
        }

        /* For Better Button Layout */
        td a {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        /* Message Display */
        .message {
            padding: 10px;
            background-color: #d4edda;
            border-radius: 4px;
            color: #155724;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .error {
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
            color: #721c24;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <h1>Manage Books</h1>
    <?php if (isset($_SESSION['message'])) { echo $_SESSION['message']; unset($_SESSION['message']); } ?>
    <?php if (isset($_SESSION['error'])) { echo $_SESSION['error']; unset($_SESSION['error']); } ?>
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Condition</th>
            <th>Description</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($book = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo $book['genre']; ?></td>
            <td><?php echo $book['condition']; ?></td>
            <td><?php echo $book['description']; ?></td>
            <td><?php echo $book['location']; ?></td>
            <td><?php echo $book['status']; ?></td>
            <td>
                <a href="manage_books.php?action=approve&book_id=<?php echo $book['id']; ?>" class="approve">Approve</a> |
                <a href="manage_books.php?action=delete&book_id=<?php echo $book['id']; ?>" class="delete">Delete</a> |
                <a href="manage_books.php?action=reject&book_id=<?php echo $book['id']; ?>" class="reject">Reject</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</body>
</html>
