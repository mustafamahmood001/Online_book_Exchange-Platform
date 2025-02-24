<?php
session_start();
include('../config/config.php');

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    echo "You must be logged in to view queries.";
    exit();
}

// Fetch all queries from the database
$sql = "SELECT id, user_id, query, response, status, created_at FROM support_tickets ORDER BY created_at DESC";
$result = $conn->query($sql);

// Display the queries
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='query-item'>";
        echo "<h4>User ID: " . htmlspecialchars($row['user_id']) . "</h4>";
        echo "<p><strong>Query:</strong> " . htmlspecialchars($row['query']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
        echo "<p><strong>Response:</strong> " . ($row['response'] ? htmlspecialchars($row['response']) : 'No response yet') . "</p>";
        echo "<p><strong>Submitted on:</strong> " . $row['created_at'] . "</p>";

        // Only show reply button and form for open queries
        if ($row['status'] == 'open') {
            echo "<button class='reply-btn' data-id='" . $row['id'] . "'>Reply</button>";
            echo "<form class='response-form' data-id='" . $row['id'] . "' style='display: none;'>";
            echo "<textarea placeholder='Enter your response'></textarea><br>";
            echo "<button type='submit'>Submit Reply</button>";
            echo "</form>";
        }
        echo "</div>";
    }
} else {
    echo "<p>No queries found.</p>";
}

$conn->close();
?>
