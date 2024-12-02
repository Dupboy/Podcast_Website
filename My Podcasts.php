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

// Include database configuration
include('configure.php');

$uploadError = '';
$uploadSuccess = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['podcastTitle'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $type = $_POST['type'];
    $userId = $_SESSION['user_id'];

    // Handle Thumbnail Upload
    $thumbnail = $_FILES['thumbnail'];
    $thumbnailPath = null;
    if (!empty($thumbnail['name'])) {
        $thumbnailDir = "uploads/thumbnails/";
        $thumbnailPath = $thumbnailDir . basename($thumbnail['name']);
        if (!move_uploaded_file($thumbnail['tmp_name'], $thumbnailPath)) {
            $uploadError = "Failed to upload thumbnail.";
        }
    }

    // Handle Content Upload
    $fileName = null;
    $textContent = null;

    if ($type === 'Audio' || $type === 'Video') {
        // Handle File Upload
        $file = $_FILES['fileUpload'];
        if (!empty($file['name'])) {
            $fileDir = "uploads/" . strtolower($type) . "/";
            $fileName = $fileDir . basename($file['name']);
            if (!move_uploaded_file($file['tmp_name'], $fileName)) {
                $uploadError = "Failed to upload file.";
            }
        }
    } elseif ($type === 'Text') {
        // Handle Text Content
        $textContent = $_POST['textContent'];
    }

    // Insert into Database
    if (empty($uploadError)) {
        $stmt = $conn->prepare("INSERT INTO uploads (user_id, title, description, genre, type, file_name, text_content, thumbnail) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $userId, $title, $description, $genre, $type, $fileName, $textContent, $thumbnailPath);
        if ($stmt->execute()) {
            $uploadSuccess = true;
        } else {
            $uploadError = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Podcasts - Zetech Podcast Website</title>
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

        /* Form Section */
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-container form input,
        .form-container form textarea,
        .form-container form select,
        .form-container form button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-container form button {
            background-color: #6a0dad; /* Purple color */
            color: white;
            font-size: 16px;
            cursor: pointer;
            border: none;
        }

        .form-container form button:hover {
            background-color: #7a1eda;
        }

        /* Error and Success Messages */
        .message {
            font-size: 14px;
            margin-top: 10px;
        }

        .message.error {
            color: red;
        }

        .message.success {
            color: green;
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
            <a href="My Podcasts.php?logout=true" class="logout-button">Log out</a>
        </div>
    </div>

    <div class="form-container">
        <h2>Upload a Podcast</h2>
        <?php if ($uploadSuccess): ?>
            <p class="message success">Podcast uploaded successfully!</p>
        <?php elseif ($uploadError): ?>
            <p class="message error"><?php echo $uploadError; ?></p>
        <?php endif; ?>
        <form action="My Podcasts.php" method="post" enctype="multipart/form-data">
            <input type="text" name="podcastTitle" placeholder="Enter content title" required>
            <textarea name="description" placeholder="Enter content description" rows="3" required></textarea>
            
            <div style="display: flex; gap: 15px;">
                <select name="genre" required>
                    <option value="" disabled selected>Select genre</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Education">Education</option>
                    <option value="Technology">Technology</option>
                    <option value="Lifestyle">Lifestyle</option>
                </select>
                <select name="type" id="contentType" required onchange="toggleUploadFields()">
                    <option value="Audio">Audio</option>
                    <option value="Video">Video</option>
                    <option value="Text">Text</option>
                </select>
            </div>
            
            <div id="fileUploadField">
                <label for="fileUpload">Upload File</label>
                <input type="file" name="fileUpload">
            </div>

            <div id="textContentField" style="display: none;">
                <label for="textContent">Enter Text Content</label>
                <textarea name="textContent" placeholder="Write your content here..." rows="5"></textarea>
            </div>
            
            <label for="thumbnail">Select Thumbnail</label>
            <input type="file" name="thumbnail" required>

            <button type="submit">Upload Content</button>
        </form>
    </div>

    <script>
        function toggleUploadFields() {
            const type = document.getElementById('contentType').value;
            const fileField = document.getElementById('fileUploadField');
            const textField = document.getElementById('textContentField');

            if (type === 'Text') {
                fileField.style.display = 'none';
                textField.style.display = 'block';
            } else {
                fileField.style.display = 'block';
                textField.style.display = 'none';
            }
        }
    </script>
</body>
</html>
