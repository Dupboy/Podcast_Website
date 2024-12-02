<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php'); // Redirect to login after logout
    exit();
}

require 'configure.php'; // Include the database connection

// Fetch queued podcasts for the logged-in user
$userId = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare(
        "SELECT pq.id AS queue_id, u.id AS podcast_id, u.title, u.description, u.type 
         FROM podcast_queue pq 
         JOIN uploads u ON pq.podcast_id = u.id 
         WHERE pq.user_id = ? 
         ORDER BY pq.added_at DESC"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $queueItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    $queueItems = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Queue - Zetech Podcast Website</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 20px;
            background-color: #fff;
            border-bottom: 1px solid #ccc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #0047ab; /* Blue color for title */
            font-size: 24px;
            margin: 0;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: #000;
            font-size: 14px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .logout-button {
            background-color: #0047ab;
            color: white;
            margin-top: -6px;
            padding: 8px 8px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #005bb5;
        }

        .queue-container {
            text-align: center;
        }

        .queue-title {
            font-size: 22px;
            color: #333;
            margin-bottom: 20px;
        }

        .queue-item {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: left;
        }

        .delete-button {
            display: inline-block;
            margin-top: 10px;
            background-color: #e63946; /* Red color for delete */
            color: white;
            padding: 8px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .delete-button:hover {
            background-color: #d62828;
        }

        .empty-queue {
            font-size: 16px;
            color: #888;
            margin-top: 30px;
        }

        .podcast-link {
            color: #0047ab;
            font-weight: bold;
            text-decoration: none;
        }

        .podcast-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Zetech Podcast Website</h1>
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <a href="Discover.php">Discover</a>
            <a href="My Podcasts.php">My Podcasts</a>
            <a href="My Queue.php">My Queue</a>
            <a href="My Queue.php?logout=true" class="logout-button">Log out</a>
        </div>
    </div>

    <div class="content">
        <div class="queue-container">
            <div class="queue-title">My Queue</div>
            <?php if (!empty($queueItems)): ?>
                <?php foreach ($queueItems as $item): ?>
                    <div class="queue-item">
                        <p><strong>Podcast Title:</strong> 
                            <a href="Home.php?play=<?= $item['podcast_id'] ?>" class="podcast-link">
                                <?= htmlspecialchars($item['title']) ?>
                            </a>
                        </p>
                        <p><strong>Description:</strong> <?= htmlspecialchars($item['description']) ?></p>
                        <a href="remove_from_queue.php?queue_id=<?= $item['queue_id'] ?>" class="delete-button">Delete</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-queue">There is nothing in your queue.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
