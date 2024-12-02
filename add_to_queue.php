<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
require 'configure.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid podcast ID.";
    exit();
}

$podcastId = $_GET['id'];
$userId = $_SESSION['user_id'];

try {
    // Insert podcast into the queue if it doesn't already exist
    $sql = "INSERT INTO podcast_queue (user_id, podcast_id) 
            SELECT ?, ? FROM DUAL
            WHERE NOT EXISTS (SELECT 1 FROM podcast_queue WHERE user_id = ? AND podcast_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $userId, $podcastId, $userId, $podcastId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header('Location: My Queue.php'); // Redirect to My Queue page after adding
    } else {
        echo "This podcast is already in your queue.";
    }

    $stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "An error occurred while adding to the queue.";
}

$conn->close();
?>
