<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<div class="sidebar">
    <a href="Home.php">Home</a>
    <a href="Discover.php">Discover</a>
    <a href="My Queue.php">My Queue</a>
    <a href="My Podcasts.php">My Podcasts</a>
    <a href="Recents.php">Recents</a>
</div>

<div class="header">
    <h1>Zetech Podcast Website</h1>
    <p>A place for insightful conversations and stories.</p>
    <div class="auth-buttons">
        <a href="Home.php?logout=true" class="logout-button">Log out</a>
    </div>
</div>
