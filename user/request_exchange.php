<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch user information
$email = $_SESSION['user'];
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestedBookId = $_POST['book_id'];
    $reason = $_POST['reason'];
    $userId = $user['id'];

    // Check if the user selected a book and a reason
    if (empty($requestedBookId) || empty($reason)) {
        $message = "<p class='error'>Please select a book and a reason to request an exchange.</p>";
    } else {
        // If "Other" is selected, validate that the custom reason is provided
        if ($reason === 'other' && empty($_POST['other_reason'])) {
            $message = "<p class='error'>Please provide a custom reason.</p>";
        } else {
            $finalReason = ($reason === 'other') ? $_POST['other_reason'] : $reason;

            // Insert the exchange request into the database
            $insertQuery = "INSERT INTO exchange_requests (user_id, book_id, reason, status, requested_on) VALUES (?, ?, ?, 'pending', NOW())";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param('iis', $userId, $requestedBookId, $finalReason);

            if ($stmt->execute()) {
                $message = "<p class='success'>Exchange request submitted successfully!</p>";
            } else {
                $message = "<p class='error'>Error submitting your request. Please try again later.</p>";
            }
        }
    }
}

// Fetch all books except the user's own books for exchange
$bookQuery = "SELECT * FROM books WHERE user_id != ?";
$stmt = $conn->prepare($bookQuery);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$books = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Exchange</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            color: #444;
        }
        form {
            margin: 20px 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        select, button, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f4f4f9;
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
        footer a {
            text-decoration: none;
            color: #007bff;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Request a Book Exchange</h1>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="book_id">Select a Book:</label>
                <select name="book_id" id="book_id" required>
                    <option value="">-- Select a Book --</option>
                    <?php while ($book = $books->fetch_assoc()): ?>
                        <option value="<?php echo $book['id']; ?>">
                            <?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="reason">Select a Reason for Exchange:</label>
                <select name="reason" id="reason" required>
                    <option value="">-- Select a Reason --</option>
                    <option value="damaged">Damaged</option>
                    <option value="duplicate">Duplicate</option>
                    <option value="did_not_like">Did Not Like</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group" id="other_reason_group" style="display: none;">
                <label for="other_reason">Please Specify Your Reason:</label>
                <textarea name="other_reason" id="other_reason" rows="4"></textarea>
            </div>

            <button type="submit">Submit Request</button>
        </form>

        <footer>
            <a href="dashboard.php">Back to Dashboard</a>
        </footer>
    </div>

    <script>
        // Show/Hide "Other" reason text field based on selection
        document.getElementById('reason').addEventListener('change', function() {
            var otherReasonGroup = document.getElementById('other_reason_group');
            if (this.value === 'other') {
                otherReasonGroup.style.display = 'block';
            } else {
                otherReasonGroup.style.display = 'none';
            }
        });
    </script>
</body>
</html>
