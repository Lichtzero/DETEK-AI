<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "deter");
if (isset($_SESSION['user_ses'])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <br>
        <title>DETEK-AI</title>
        <link rel="stylesheet" href="folder.css">
        <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@500&family=Roboto:wght@500&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/80ebb5657c.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="menu">
            <input type="checkbox" id="toggle">
            <label for="toggle">&#9776;</label>
            <p>DETEK-AI</p>
            <div class="right"> <a href="logout.php">Signout</a></div>
            <div class="line"></div>
            <nav>
                <ul>
                    <li><a href="main.php">Home</a></li>
                    <li><a href="uploadscan.php">Upload File</a></li>
                    <li><a href="new.php">Upload Folder</a></li>
                    <li><a href="history.php">History</a></li>
                </ul>
            </nav>
        </div>
        <div class="user">
            <div class="left">
                <i class="fa-regular fa-user fa-xl"></i>
                <?php echo 'Welcome, ' . $_SESSION['user_ses']; ?>
            </div>
        </div>
        <div class="container">
            <?php
            $targetDir = "uploads/";

            // Function to clear the folder
            function clearFolder($targetDir)
            {
                // Check if the target directory exists
                if (file_exists($targetDir) && is_dir($targetDir)) {
                    // Open the target directory
                    if ($dh = opendir($targetDir)) {
                        // Loop through all files in the directory
                        while (($file = readdir($dh)) !== false) {
                            // Exclude current directory (.) and parent directory (..)
                            if ($file != '.' && $file != '..') {
                                // Construct the full path of the file
                                $filePath = $targetDir . $file;
                                // Check if the file is a regular file
                                if (is_file($filePath)) {
                                    // Delete the file
                                    unlink($filePath);
                                }
                            }
                        }
                        // Close the directory handle
                        closedir($dh);
                        // Display success message
                        // echo "<script>alert('Target folder cleared successfully.');</script>";
                    } else {
                        // Display error message if unable to open directory
                        echo "<script>alert('Unable to open target folder.');</script>";
                    }
                } else {
                    // Display error message if target directory does not exist
                    echo "<script>alert('Target folder does not exist.');</script>";
                }
            }

            // Check if the directory exists, if not, create it
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true); // Creates the directory recursively
            }

            if (isset($_POST["submit"])) {
                // Clear the folder before performing the scan
                clearFolder($targetDir);

                // Move uploaded images to target directory
                if (!empty($_FILES['folder']['name'])) {
                    $files = $_FILES['folder'];
                    $file_count = count($files['name']);
                    $successCount = 0;

                    for ($i = 0; $i < $file_count; $i++) {
                        $targetFile = $targetDir . basename($files['name'][$i]);
                        if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                            $successCount++;
                        }
                    }

                    if ($successCount === $file_count) {
                        echo "<script>alert('All files uploaded successfully.');</script>";
                    } else {
                        echo "<script>alert('Failed to upload all files.');</script>";
                    }
                } else {
                    echo "<script>alert('No files selected.');</script>";
                }
                // Run the scanning process after uploading files
                $python_executable = "/opt/homebrew/opt/python@3.11/bin/python3.11";
                $detect_script = "/Applications/XAMPP/xamppfiles/htdocs/DETEK-AI/detect.py";

                // Execute Python script and capture the output
                $output = shell_exec("$python_executable $detect_script 2>&1");
                // Output any result or message
                echo '<div class="scan-results">';
                echo '<p>Scan Results:</p>';
                echo '<pre>' . htmlentities($output) . '</pre>';
                echo '</div>';
            }
            ?>

            <div id="dragger">
                <p>Select folder to scan <br><br></p>
                <form method="post" enctype="multipart/form-data">
                    <!-- <label for="folder">Select a folder to upload:</label> -->
                    <input type="file" id="folder" name="folder[]" webkitdirectory directory multiple>
                    <input type="submit" value="Scan" name="submit">
                </form>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    echo "<script>alert('Please Login to continue');</script>";
    header('location:logout.php');
}
?>