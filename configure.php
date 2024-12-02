<?php
// configure.php

// Database configuration
$servername = "localhost";
$username = "madmax"; // Replace with your DB username if different
$password = "*Conex001*"; // Replace with your DB password if set
$dbname = "Podcast_Website";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
