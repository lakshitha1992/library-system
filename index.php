<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            display: flex;
            flex-direction: column;
            /* Added for footer positioning */
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Arial', sans-serif;
            margin: 0;
            /* Remove default body margin */
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: #f0f2f5;
            /* Light background for better contrast */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            /* Allow footer to be at bottom */
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Ensure full height coverage */
            background-size: cover;
            /* Ensures the image covers the entire screen */
            background-position: center;
            /* Centers the background image */
            background-repeat: no-repeat;
            /* Prevents the image from repeating */
            background-attachment: fixed;
            /* Keeps the image fixed during scroll */
            position: relative;
            /* Set the body to relative for layering */

            /* Adding background image in a pseudo-element for opacity */
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('assets/img/index_background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.75;
            /* Set opacity of background image */
            z-index: -1;
            /* Ensures the background stays behind content */
        }

        .card {
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 20px;
            background-color: white;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            font-weight: 700;
            font-size: 28px;
            color: #333;
            margin-bottom: 30px;
        }

        .btn-custom {
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 30px;
            width: 100%;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .btn-custom:hover {
            transform: scale(1.05);
        }

        .btn-admin {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .btn-user {
            background: linear-gradient(135deg, #28a745, #19692c);
            color: white;
        }

        .btn-admin:hover,
        .btn-user:hover {
            opacity: 0.9;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.75);
            /* Set footer background color with opacity */
            position: absolute;
            /* Dock at the bottom */
            bottom: 0;
            /* Align to the bottom */
            width: 100%;
            text-align: center;
            color: #fff;
            padding: 10px 0;
            /* Add some padding */
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            display: inline;
            margin: 0 10px;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="card text-center">
        <h2>Library System</h2>
        <a href="admin.php" class="btn btn-admin btn-custom mb-3">Admin Login</a>
        <a href="user.php" class="btn btn-user btn-custom">User Login</a>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Library System. All rights reserved.</p>
        <p>The System is Designed by: <a href="https://lk.linkedin.com/in/lakshitha-dulanjaya"> Lakshitha D.
                Hemasinghe</a></p>
        <ul class="footer-links">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>