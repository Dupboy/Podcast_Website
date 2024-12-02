<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'configure.php';

$queueId = isset($_GET['queue_id']) ? (int)$_GET['queue_id'] : 0;

if ($queueId > 0) {
    try {
        $stmt = $conn->prepare("DELETE FROM podcast_queue WHERE id = ?");
        $stmt->bind_param("i", $queueId);
        $stmt->execute();
        $stmt->close();
        header("Location: My Queue.php");
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "Error removing from queue.";
    }
} else {
    echo "Invalid queue ID.";
}
?>
