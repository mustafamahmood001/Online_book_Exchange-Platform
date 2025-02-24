<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    echo "You must be logged in to submit a query.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $query = $_POST['query'];

    // Insert the query into the database
    $sql = "INSERT INTO support_tickets (user_id, query, status) VALUES (?, ?, 'open')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $userId, $query);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Query submitted successfully!";
    } else {
        echo "Error submitting query.";
    }

    $stmt->close();
    $conn->close();
}
?>
