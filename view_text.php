<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'configure.php';

// Fetch the text content based on the provided ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT title, text_content FROM uploads WHERE id = ? AND type = 'Text'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $textPodcast = $result->fetch_assoc();
    $stmt->close();

    if (!$textPodcast) {
        echo "<p>Content not found.</p>";
        exit();
    }
} else {
    echo "<p>Invalid request.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($textPodcast['title']) ?></title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            font-size: 24px;
            color: #0047ab;
            margin-bottom: 20px;
            border-bottom: 2px solid #0047ab;
            padding-bottom: 10px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            text-decoration: none;
            background-color: #0047ab;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        a:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($textPodcast['title']) ?></h1>
        <p><?= nl2br(htmlspecialchars($textPodcast['text_content'])) ?></p>
        <a href="Home.php">Go Back</a>
    </div>
</body>
</html>
