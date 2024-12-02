<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

require 'configure.php';

// Fetch all unique genres (categories) from the uploads table for filtering
$sqlGenres = "SELECT DISTINCT genre FROM uploads";
$resultGenres = $conn->query($sqlGenres);

// Fetch media (uploads) with genre information
$sqlUploads = "SELECT id, title, description, thumbnail, type, genre FROM uploads";
$resultUploads = $conn->query($sqlUploads);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zetech Podcast Website</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header Styles */
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
            color: #000; /* Black color */
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

        /* Filter Buttons */
        .filters {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            padding: 0 20px;
        }

        .filter-button {
            padding: 8px 15px;
            border: none;
            background-color: white;
            border-radius: 5px;
            color: #000;
            font-size: 14px;
            cursor: pointer;
        }

        .filter-button:hover {
            background-color: #e0e0e0;
        }

        .filter-button.active {
            background-color: #0047ab;
            color: white;
        }

        /* Cards Layout */
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: left;
        }

        .card {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: white;
            max-width: 300px;
        }

        .card img {
            width: 100%;
            height: 180px; /* Consistent height */
            object-fit: cover; /* Ensure no stretching or distortion */
            border-bottom: 1px solid #ddd;
        }

        .card .card-content {
            padding: 15px;
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

        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #0047ab;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .card a:hover {
            background-color: #005bb5;
        }

        /* Add to Queue Button */
        .card .add-to-queue {
            margin-top: 10px;
            padding: 8px 12px;
            border: 1px solid #0047ab;
            color: #0047ab;
            background-color: transparent;
            text-align: center;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .card .add-to-queue:hover {
            background-color: #e6f0ff;
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
            <a href="Home.php?logout=true" class="logout-button">Log out</a>
        </div>
    </div>

    <div class="filters">
        <button class="filter-button active" onclick="filterCategory('all')">All</button>
        <?php while ($genre = $resultGenres->fetch_assoc()): ?>
            <button class="filter-button" onclick="filterCategory('<?= strtolower($genre['genre']) ?>')"><?= htmlspecialchars($genre['genre']) ?></button>
        <?php endwhile; ?>
    </div>

    <div class="cards-container" id="cards-container">
        <?php while ($upload = $resultUploads->fetch_assoc()): ?>
            <div class="card" data-category="<?= strtolower($upload['genre']) ?>">
                <img src="<?= htmlspecialchars($upload['thumbnail']) ?>" alt="<?= htmlspecialchars($upload['title']) ?>">
                <div class="card-content">
                    <h3><?= htmlspecialchars($upload['title']) ?></h3>
                    <p><?= htmlspecialchars($upload['description']) ?></p>
                    <a href="#">Watch Now</a>
                    <button class="add-to-queue">Add to Queue</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function filterCategory(category) {
            const cardsContainer = document.getElementById('cards-container');
            const filterButtons = document.querySelectorAll('.filter-button');
            filterButtons.forEach(button => button.classList.remove('active'));

            if (category === 'all') {
                // Show all cards
                Array.from(cardsContainer.children).forEach(card => card.style.display = 'block');
            } else {
                // Show only matching cards based on category (genre)
                Array.from(cardsContainer.children).forEach(card => {
                    card.style.display = card.getAttribute('data-category') === category ? 'block' : 'none';
                });
            }

            // Highlight the active button
            document.querySelector(`[onclick="filterCategory('${category}')"]`).classList.add('active');
        }
    </script>
</body>
</html>
