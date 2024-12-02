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

// Database connection
require 'configure.php';

$userId = $_SESSION['user_id'];

try {
    // Fetch featured podcasts
    $sql = "SELECT * FROM uploads WHERE featured = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $featuredFlag = 1;
    $stmt->bind_param("i", $featuredFlag);
    $stmt->execute();
    $featuredPodcasts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Fetch user-uploaded podcasts excluding featured ones
    $sql = "SELECT * FROM uploads WHERE user_id = ? AND featured = 0 ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $userPodcasts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

} catch (Exception $e) {
    error_log($e->getMessage());
    echo "<p>Sorry, there was an error fetching the podcasts. Please try again later.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Zetech Podcast Website</title>
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
            color: #000; /* Blue color for title */
            font-size: 24px;
            margin: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: #000;
            margin-left: 15px;
            font-size: 14px;
        }

        .logout-button {
            background-color: #0047ab;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #005bb5;
        }

        .section {
            padding: 20px;
        }

        .section h2 {
            margin-bottom: 10px;
            font-size: 24px;
            color: #0047ab;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }

        .card {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: white;
            width: 250px; /* Increased width */
            height: auto; /* Allow card to grow based on content */
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;  /* Ensure padding and border are included in width/height */
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .thumbnail {
            width: 100%;
            height: 250px;
            background-size: cover;
            background-position: center;
            cursor: pointer;
        }

        .media-container {
            height: 180px;
            display: none;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f4;
            width: 100%; /* Ensures full width of the card */
        }

        video, audio {
            max-height: 100%;
            max-width: 100%;
        }

        .card-content {
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1; /* Allow content to grow and fill available space */
        }

        .card h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .card p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .card .action-button {
            background-color: #0047ab;
            color: white;
            border: none;
            padding: 8px 12px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
        }

        .card .action-button:hover {
            background-color: #005bb5;
        }

        .audio-player {
            margin-top: 15px;
        }

        .button-container {
            display: flex;
            gap: 10px; /* Adjust space between buttons */
            margin-top: 10px;
        }
    </style>

    <script>
        function toggleMedia(podcastId) {
            var mediaContainer = document.getElementById('media-' + podcastId);
            var thumbnail = document.getElementById('thumbnail-' + podcastId);
            var card = document.getElementById('card-' + podcastId); // Get the card element

            // Toggle media visibility
            mediaContainer.style.display = 'flex';
            thumbnail.style.display = 'none';

            // Adjust card height if needed
            card.style.height = 'auto'; // Allow card height to expand when media is displayed
        }
    </script>
</head>

<body>
    <div class="header">
        <h1>Zetech Podcast Website</h1>
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <a href="Discover.php">Discover</a>
            <a href="My Podcasts.php">My Podcasts</a>
            <a href="My Queue.php">My Queue</a> <!-- Added Queue Button -->
            <a href="Home.php?logout=true" class="logout-button">Log out</a>
        </div>
    </div>

    <!-- Featured Podcasts Section -->
    <div class="section">
        <h2>Featured Podcasts</h2>
        <div class="cards-container">
            <?php foreach ($featuredPodcasts as $podcast): ?>
                <div class="card" id="card-<?= $podcast['id'] ?>">
                    <div class="thumbnail" id="thumbnail-<?= $podcast['id'] ?>" style="background-image: url('<?= htmlspecialchars($podcast['thumbnail']) ?>');"></div>
                    <div class="media-container" id="media-<?= $podcast['id'] ?>">
                        <?php if ($podcast['type'] === 'Video'): ?>
                            <video controls>
                                <source src="<?= htmlspecialchars($podcast['file_name']) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php elseif ($podcast['type'] === 'Audio'): ?>
                            <audio controls>
                                <source src="<?= htmlspecialchars($podcast['file_name']) ?>" type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>
                        <?php elseif ($podcast['type'] === 'Text'): ?>
                            <p>Text Content Available</p>
                            <a href="view_text.php?id=<?= $podcast['id'] ?>" class="action-button">Read Now</a>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3><?= htmlspecialchars($podcast['title']) ?></h3>
                        <p><?= htmlspecialchars($podcast['description']) ?></p>
                        <div class="button-container">
                            <?php if ($podcast['type'] === 'Audio'): ?>
                                <button class="action-button" onclick="toggleMedia(<?= $podcast['id'] ?>)">Listen Now</button>
                            <?php elseif ($podcast['type'] === 'Video'): ?>
                                <button class="action-button" onclick="toggleMedia(<?= $podcast['id'] ?>)">Watch Now</button>
                            <?php elseif ($podcast['type'] === 'Text'): ?>
                                <a href="view_text.php?id=<?= $podcast['id'] ?>" class="action-button">Read Now</a>
                            <?php endif; ?>
                            <a href="add_to_queue.php?id=<?= $podcast['id'] ?>" class="action-button">Add to Queue</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- User Podcasts Section -->
    <div class="section">
        <h2>Your Podcasts</h2>
        <div class="cards-container">
            <?php foreach ($userPodcasts as $podcast): ?>
                <div class="card" id="card-<?= $podcast['id'] ?>">
                    <div class="thumbnail" id="thumbnail-<?= $podcast['id'] ?>" style="background-image: url('<?= htmlspecialchars($podcast['thumbnail']) ?>');"></div>
                    <div class="media-container" id="media-<?= $podcast['id'] ?>">
                        <?php if ($podcast['type'] === 'Video'): ?>
                            <video controls>
                                <source src="<?= htmlspecialchars($podcast['file_name']) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php elseif ($podcast['type'] === 'Audio'): ?>
                            <audio controls>
                                <source src="<?= htmlspecialchars($podcast['file_name']) ?>" type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>
                        <?php elseif ($podcast['type'] === 'Text'): ?>
                            <p>Text Content Available</p>
                            <a href="view_text.php?id=<?= $podcast['id'] ?>" class="action-button">Read Now</a>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3><?= htmlspecialchars($podcast['title']) ?></h3>
                        <p><?= htmlspecialchars($podcast['description']) ?></p>
                        <div class="button-container">
                            <?php if ($podcast['type'] === 'Audio'): ?>
                                <button class="action-button" onclick="toggleMedia(<?= $podcast['id'] ?>)">Listen Now</button>
                            <?php elseif ($podcast['type'] === 'Video'): ?>
                                <button class="action-button" onclick="toggleMedia(<?= $podcast['id'] ?>)">Watch Now</button>
                            <?php elseif ($podcast['type'] === 'Text'): ?>
                                <a href="view_text.php?id=<?= $podcast['id'] ?>" class="action-button">Read Now</a>
                            <?php endif; ?>
                            <a href="add_to_queue.php?id=<?= $podcast['id'] ?>" class="action-button">Add to Queue</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
