<?php
session_start();

// Check if Admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php'); // Redirect to login page if not authenticated
    exit();
}

// Include database connection
require_once '../config/config.php';

// Handle actions (approve, block, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        // Correct query for approving user
        $stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
    } elseif ($action === 'block') {
        // Correct query for blocking user
        $stmt = $conn->prepare("UPDATE users SET status = 'blocked' WHERE id = ?");
    } elseif ($action === 'delete') {
        // Correct query for deleting user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    } else {
        $stmt = null;
    }

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect back to the same page to refresh the list
    header("Location: manage_users.php");
    exit();
}

// Edit User Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $user_id = intval($_POST['user_id']);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch current password if no new password is provided
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_password);
    $stmt->fetch();
    $stmt->close();

    // If password is provided, hash it
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
    } else {
        // Otherwise, keep the existing password
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php");
    exit();
}

// Fetch all users from the database
$stmt = $conn->prepare("SELECT * FROM users ORDER BY id ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #3498db;
            text-align: center;
            margin-bottom: 20px;
        }

        .back-btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .approve-btn,
        .block-btn,
        .delete-btn,
        .edit-btn {
            padding: 8px 15px;
            margin: 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .approve-btn {
            background-color: #28a745;
            color: white;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .block-btn {
            background-color: #ffc107;
            color: white;
        }

        .block-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        #edit-user-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
        }

        #edit-user-form h2 {
            color: #3498db;
            text-align: center;
            margin-bottom: 20px;
        }

        #edit-user-form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        #edit-user-form button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        #edit-user-form button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        <h1>Manage Users</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <form method="POST" action="manage_users.php" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                            <button type="submit" name="action" value="block" class="block-btn">Block</button>
                            <button type="submit" name="action" value="delete" class="delete-btn">Delete</button>
                        </form>
                        <form method="POST" action="manage_users.php" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                            <button type="submit" name="edit_user_form" class="edit-btn">Edit</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit User Form -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user_form'])): ?>
    <div id="edit-user-form">
        <h2>Edit User</h2>
        <form method="POST" action="manage_users.php">
            <input type="hidden" name="user_id" value="<?php echo $_POST['user_id']; ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name']); ?>" placeholder="Name" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email']); ?>" placeholder="Email" required>
            <input type="password" name="password" placeholder="New Password (Leave blank to keep current)">
            <button type="submit" name="edit_user">Update User</button>
        </form>
    </div>
    <?php endif; ?>
</body>
</html>
