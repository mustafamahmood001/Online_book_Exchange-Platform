<?php
session_start();
include('../config/config.php');

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    echo "You must be logged in to resolve queries.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $queryId = $_POST['ticket_id'];
    $response = $_POST['response'];

    // Update the query's status to resolved and add the response
    $sql = "UPDATE support_tickets SET status = 'resolved', response = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $response, $queryId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Query resolved successfully!";
    } else {
        echo "Error resolving query.";
    }

    $stmt->close();
    $conn->close();
}
?>
