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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Zetech Podcast Website</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="sidebar">
    <a href="Home.php">Home</a>
    <a href="Discover.php">Discover</a>
    <a href="My Queue.php">My Queue</a>
    <a href="My Podcasts.php">My Podcasts</a>
    <a href="Recents.php">Recents</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Zetech Podcast Website</h1>
        <p>A place for insightful conversations and stories.</p>
        <div class="auth-buttons">
            <a href="Home.php?logout=true" class="logout-button">Log out</a>
        </div>
    </div>

    <section class="trending">
        <h2>Featured Podcasts</h2>
        <div class="podcast-slider">
            <!-- Video podcast items -->
            <div class="podcast-item">
                <video controls poster="rich_vs_poor.jpg" width="100%">
                    <source src="Assets/Videos/Mindset" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <h3>Rich vs Poor Mindset</h3>
                <p>Join us as we explore the world of finance and investing.</p>
            </div>
            <div class="podcast-item">
                <video controls poster="Assets/Ai.webp" width="100%">
                    <source src="Assets/Videos/AI.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <h3>What is AI?</h3>
                <p>Discover the latest in technology and innovation.</p>
            </div>
            <div class="podcast-item">
                <video controls poster="culture_shock.jpg" width="100%">
                    <source src="videos/culture_shock.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <h3>Culture Shock and Experience</h3>
                <p>Engage with stories from different cultures and perspectives.</p>
            </div>
        </div>
    </section>

    <section class="genres">
        <h2>Browse by Genre</h2>
        <ul>
            <!-- Genre items with images -->
            <li>
                <a href="genre.php?genre=webdev">
                    <img src="Assets/images/web.jpg" alt="Web Development">
                    <span>Web Development</span>
                </a>
            </li>
            <li>
                <a href="genre.php?genre=comedy">
                    <img src="Assets/images/comedy2.jpg" alt="Comedy">
                    <span>Comedy</span>
                </a>
            </li>
            <li>
                <a href="genre.php?genre=education">
                    <img src="Assets/images/education.jpg" alt="Education">
                    <span>Education</span>
                </a>
            </li>
            <li>
                <a href="genre.php?genre=religion">
                    <img src="Assets/images/religion.jpg" alt="Religion">
                    <span>Religion</span>
                </a>
            </li>
            <li>
                <a href="genre.php?genre=robotics">
                    <img src="Assets/images/robots.jpg" alt="Robotics">
                    <span>Robotics</span>
                </a>
            </li>
            <li>
                <a href="genre.php?genre=ai">
                    <img src="Assets/images/AI.jpg" alt="AI">
                    <span>AI</span>
                </a>
            </li>
        </ul>
    </section>

    <section class="about">
        <h2>About Zetech Podcast</h2>
        <p>At Zetech Podcast, we believe in the power of stories to inspire, educate, and entertain. Our platform is a hub for discussions on various topics, ranging from technology and finance to culture and comedy. Join our community and explore a diverse range of podcasts tailored to your interests.</p>
    </section>
</div>

</body>
</html>
