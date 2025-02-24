<?php
session_start();
if (!isset($_SESSION['admin'])) {
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
    <title>Admin Query Management Panel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f4f7fb;
        }

        .back-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            display: inline-block;
            text-decoration: none;
            margin: 20px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 28px;
            color: #333;
        }

        section {
            max-width: 1000px;
            margin: 30px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        section h3 {
            color: #444;
            margin-bottom: 20px;
        }

        #queryList {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .query-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .query-item:hover {
            background-color: #f8f8f8;
        }

        .query-item h4 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .query-item p {
            font-size: 15px;
            margin: 10px 0;
        }

        .response-form {
            margin-top: 10px;
            display: none;
        }

        .response-form textarea {
            width: 100%;
            height: 120px;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: none;
        }

        .response-form button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        .response-form button:hover {
            background-color: #0056b3;
        }

        .reply-btn {
            padding: 8px 16px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        .reply-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Query Management Panel</h1>
    </header>

    <section>
        <h3>User Queries</h3>
        <div id="queryList">
            <!-- Queries will be loaded here dynamically -->
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            loadQueries();

            function loadQueries() {
                $.ajax({
                    url: 'get_all_queries.php',
                    method: 'GET',
                    success: function (response) {
                        $('#queryList').html(response);
                    },
                    error: function () {
                        alert("An error occurred while loading queries.");
                    }
                });
            }

            $(document).on('click', '.reply-btn', function () {
                const replyForm = $(this).closest('.query-item').find('.response-form');
                replyForm.toggle();  // Toggle visibility of the reply form
            });

            $(document).on('submit', '.response-form', function (e) {
                e.preventDefault();
                const form = $(this);
                const queryId = form.data('id');
                const response = form.find('textarea').val();

                if (response.trim() !== "") {
                    $.ajax({
                        url: 'resolve_query.php',
                        method: 'POST',
                        data: { ticket_id: queryId, response: response, status: 'resolved' },
                        success: function () {
                            loadQueries();
                        },
                        error: function () {
                            alert("An error occurred while submitting the response.");
                        }
                    });
                } else {
                    alert("Please enter a response.");
                }
            });
        });
    </script>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

</body>
</html>
