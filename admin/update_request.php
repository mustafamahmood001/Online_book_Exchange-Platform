<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection file (update the path as needed)
include('../config/config.php');  // Ensure the path is correct


if (isset($_GET['action']) && isset($_GET['id'])) {
    $request_id = $_GET['id'];
    $action = $_GET['action'];

    // Update request status based on action
    if ($action == 'accept') {
        $status = 'accepted';
    } elseif ($action == 'reject') {
        $status = 'rejected';
    }

    // Update request status in database
    $query = "UPDATE book_requests SET status='$status' WHERE id='$request_id'";
    if (mysqli_query($conn, $query)) {
        header('Location: admin_requests.php');
    } else {
        echo "Error updating request: " . mysqli_error($conn);
    }
}
?>
