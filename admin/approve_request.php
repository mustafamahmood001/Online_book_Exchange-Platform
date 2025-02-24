<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection file
include('../config/config.php');

// Check if 'id' is provided in the URL
if (!isset($_GET['id'])) {
    die("Request ID is missing.");
}

$request_id = $_GET['id'];

// Update the request status to 'approved'
$update_query = "UPDATE requests SET status = 'approved' WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("i", $request_id);

if ($update_stmt->execute()) {
    header('Location: manage_requests.php');  // Redirect back to the manage requests page
    exit();
} else {
    echo "Error: " . $update_stmt->error;
}

$conn->close();
?>
