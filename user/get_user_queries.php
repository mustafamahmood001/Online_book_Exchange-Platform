<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    echo "You must be logged in to view your queries.";
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch the user's queries from the database
$sql = "SELECT id, query, response, status, created_at FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

// Display the queries
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='query-item'>";
        echo "<h4>Query: " . htmlspecialchars($row['query']) . "</h4>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
        echo "<p><strong>Response:</strong> " . ($row['response'] ? htmlspecialchars($row['response']) : 'No response yet') . "</p>";
        echo "<p><strong>Submitted on:</strong> " . $row['created_at'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No queries found.</p>";
}

$stmt->close();
$conn->close();
?>
