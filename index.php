<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('Assets/images/grab.jpg') no-repeat center center fixed;
            background-size: cover;  /* Cover the entire background */
        }

        h1 {
            color: #4B0082; /* Purple text color */
            font-size: 2.5em;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.7); /* Semi-transparent background */
            padding: 10px;
            border-radius: 8px;
        }

        /* Container Styles */
        .container {
            text-align: center;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);  /* Semi-transparent background */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 1.2em;
        }

        a {
            color: #4B0082; /* Purple link color */
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
            padding: 10px 20px;
            border: 2px solid #4B0082;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        a:hover {
            background-color: #4B0082; /* Purple hover background */
            color: white; /* White text color on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Zetech Podcast Website</h1>
        <p>
            <a href="signup.php">Sign Up</a> | 
            <a href="login.php">Log In</a>
        </p>
    </div>
</body>
</html>
