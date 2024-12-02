<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database configuration
require 'configure.php';

$message = ""; // Variable for feedback

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $message = "Username or email already exists.";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php"); // Redirect to login after successful signup
            exit();
        } else {
            $message = "Error: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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
            background: url('images/background.jpg') no-repeat center center fixed;  /* Background image */
            background-size: cover;  /* Cover the entire background */
        }

        h2 {
            color: #4B0082; /* Purple text color */
            font-size: 2em;
            margin-bottom: 20px;
        }

        /* Form Styles */
        .signup-form {
            text-align: center;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);  /* Semi-transparent background */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .signup-form input[type="text"], .signup-form input[type="email"], .signup-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .signup-form button {
            padding: 10px 20px;
            background-color: #4B0082;  /* Purple background color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signup-form button:hover {
            background-color: #9370DB;  /* Lighter purple hover background */
        }

        .message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form class="signup-form" method="POST" action="">
        <h2>Sign Up</h2>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        
        <!-- Display message -->
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
