<?php
session_start();
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query to check the user and their approval status
    $query = "SELECT id, password, status FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check if the user's account is approved
        if ($user['status'] === 'blocked') {
            $error = "Your account is blocked. Please wait for admin approval.";
        } else {
            // Check if the password is correct
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; // Set user ID in session
                $_SESSION['user'] = $email; // Store email for display
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        }
    } else {
        $error = "No user found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF;
        }
        .back-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
        p.error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h2>User Login</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <a href="register.php">Register</a>
        </form>
        <a href="../index.php" class="back-btn">Back to Index</a>
    </div>
</body>
</html>
