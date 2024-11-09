<?php
include('header.php');

session_start();
include('configure.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

// Handle file upload if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileUpload'])) {
    $title = $_POST['podcastTitle'];
    $category = $_POST['category'];
    $file = $_FILES['fileUpload'];
    $userId = $_SESSION['user_id'];

    // Set the target directory for uploads
    $targetDir = "videos/";
    $targetFile = $targetDir . basename($file['name']);

    // Check if the file is a valid video type
    $allowedTypes = ['video/mp4', 'video/ogg', 'video/webm'];
    if (in_array($file['type'], $allowedTypes)) {
        // Move the file to the target directory and insert into database
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO uploads (user_id, title, category, file_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $userId, $title, $category, $file['name']);

            if ($stmt->execute()) {
                echo "<p>Podcast uploaded successfully!</p>";
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }
    } else {
        echo "<p>Invalid file type. Only MP4, OGG, and WEBM are allowed.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Podcasts - Zetech Podcast Website</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main-content">
        <h2>Upload Your Podcast</h2>
        <form action="My Podcasts.php" method="post" enctype="multipart/form-data">
            <label for="podcastTitle">Podcast Title:</label>
            <input type="text" name="podcastTitle" id="podcastTitle" required>
            
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="Web Development">Web Development</option>
                <option value="Comedy">Comedy</option>
                <option value="Education">Education</option>
                <option value="Religion">Religion</option>
                <option value="Robotics">Robotics</option>
                <option value="AI">AI</option>
                <option value="IoT">IoT</option>
            </select>

            <label for="fileUpload">Upload Video:</label>
            <input type="file" name="fileUpload" id="fileUpload" accept="video/*" required>

            <button type="submit">Upload Podcast</button>
        </form>

        <h2>Your Uploaded Podcasts</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>File Name</th>
                    <th>Uploaded On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $userId = $_SESSION['user_id'];
                $result = $conn->query("SELECT * FROM uploads WHERE user_id = $userId ORDER BY uploaded_on DESC");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['title']}</td>
                                <td>{$row['category']}</td>
                                <td><a href='videos/{$row['file_name']}' target='_blank'>{$row['file_name']}</a></td>
                                <td>{$row['uploaded_on']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No uploads found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
