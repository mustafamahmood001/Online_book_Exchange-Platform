<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include('../config/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Support Panel</title>
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and general page layout */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fb;
            color: #333;
            line-height: 1.6;
        }

        section {
            max-width: 900px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h3 {
            color: #444;
            margin-bottom: 20px;
        }

        #queryForm {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        #query {
            width: 100%;
            height: 150px;
            padding: 12px;
            font-size: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            resize: none;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        #query:focus {
            border-color: #6c63ff;
            outline: none;
            box-shadow: 0 0 8px rgba(108, 99, 255, 0.3);
        }

        button {
            padding: 12px 20px;
            background-color: #6c63ff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #5a54e4;
        }

        #queryList {
            margin-top: 30px;
        }

        .queryItem {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .queryItem:hover {
            background-color: #f1f1f1;
        }

        /* Back to Dashboard Button */
        .back-btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 8px;
            font-size: 1rem;
            text-decoration: none;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <section>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>  <!-- Back to Dashboard Button -->
        
        <h3>Submit Your Query</h3>
        <form id="queryForm">
            <textarea id="query" name="query" placeholder="Type your query..." required></textarea>
            <button type="submit" id="submitQuery">Submit Query</button>
        </form>

        <h3>Your Queries</h3>
        <div id="queryList"></div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            loadQueries();  // Load queries when the page loads

            // Submit query via AJAX
            $('#queryForm').submit(function (e) {
                e.preventDefault();  // Prevent form submission and page reload
                
                const query = $('#query').val().trim();
                if (query !== "") {
                    $.ajax({
                        url: 'submit_query.php',
                        method: 'POST',
                        data: { query: query },
                        success: function (response) {
                            loadQueries();
                            $('#query').val('');
                        },
                        error: function () {
                            alert("An error occurred while submitting your query.");
                        }
                    });
                } else {
                    alert("Please enter a query.");
                }
            });

            // Load user queries and display them
            function loadQueries() {
                $.ajax({
                    url: 'get_user_queries.php',
                    method: 'GET',
                    success: function (response) {
                        $('#queryList').html(response);
                    },
                    error: function () {
                        alert("An error occurred while loading your queries.");
                    }
                });
            }
        });
    </script>
</body>
</html>
