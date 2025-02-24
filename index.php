<!DOCTYPE html>
<html lang="en">
<head>
    <title>Online Book Exchange</title>
    <style>
        /* General body styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            flex-direction: column;
        }

        /* Main container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Header */
        h1 {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Description and Features Section */
        .description {
            margin: 40px 0;
            font-size: 18px;
            color: #555;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .feature-box {
            padding: 20px;
            background-color: #ecf0f1;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }

        .feature-box h3 {
            font-size: 22px;
            color: #3498db;
        }

        .feature-box p {
            font-size: 16px;
            color: #7f8c8d;
        }

        /* Navigation links */
        nav {
            margin-top: 30px;
        }

        nav a {
            font-size: 18px;
            color: #3498db;
            margin: 0 15px;
            text-decoration: none;
            padding: 12px 20px;
            background-color: #ecf0f1;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background-color: #3498db;
            color: white;
            transform: translateY(-3px);
        }

        /* Footer (Optional, if needed) */
        footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #7f8c8d;
        }

        /* Responsive design */
        @media screen and (max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            .description {
                font-size: 16px;
            }

            .feature-box h3 {
                font-size: 18px;
            }

            nav a {
                font-size: 16px;
                margin: 10px;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Online Book Exchange Platform</h1>

        <!-- Description of the Platform -->
        <div class="description">
            <p>Our Online Book Exchange platform allows you to connect with other book lovers, exchange books, and discover new reads. Whether you’re a student, teacher, or casual reader, this platform is designed to help you find books you love, while sharing the ones you’re done with!</p>
        </div>

        <!-- Features of the Platform -->
        <div class="features">
            <div class="feature-box">
                <h3>Wide Variety of Books</h3>
                <p>Browse through a diverse collection of books, ranging from fiction, non-fiction, textbooks, to rare finds. We have something for everyone!</p>
            </div>
            <div class="feature-box">
                <h3>Seamless Exchange Process</h3>
                <p>Our easy-to-use interface allows you to post and exchange books with other users effortlessly. Find books you need and offer the ones you're willing to exchange!</p>
            </div>
            <div class="feature-box">
                <h3>Trusted Community</h3>
                <p>Join a community of trusted users who value honesty and integrity. Our system ensures that all exchanges are smooth and secure.</p>
            </div>
            <div class="feature-box">
                <h3>Free to Use</h3>
                <p>Sign up today and start exchanging books for free. We believe in making book sharing accessible to everyone!</p>
            </div>
        </div>

        <!-- Navigation links -->
        <nav>
            <a href="user/register.php">User Registration</a>
            <a href="user/login.php">User Login</a>
            <a href="admin/login.php">Admin Login</a>
        </nav>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Online Book Exchange Platform. All rights reserved.</p>
    </footer>
</body>
</html>
