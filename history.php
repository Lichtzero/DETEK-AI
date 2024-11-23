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
        <link rel="stylesheet" href="hy.css">
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
                <?php echo  'Welcome, ' . $_SESSION['user_ses'];
                ?>
            </div>
            <br>
            <div class="container">
                <form method="post">
                    <button type="submit" name="clear">Clear Log File</button>
                </form>
            </div>
        </div>


        <div class="container">

            <table>
                <thead>
                    <tr>
                        <th style="width:70%">Date</th>
                        <th>Time</th>
                        <th>Image Name</th>
                        <th>P Value</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $csvFilePath = '/Applications/XAMPP/xamppfiles/htdocs/DETEK-AI/scan_results.csv';

                    // Check if the file exists
                    if (file_exists($csvFilePath)) {
                        // Open the CSV file
                        if (($handle = fopen($csvFilePath, "r")) !== false) {
                            // Read the header row
                            fgetcsv($handle);
                            // Loop through the remaining rows
                            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                                echo "<tr>";
                                foreach ($data as $value) {
                                    echo "<td>" . $value . "</td>";
                                }
                                echo "</tr>";
                            }
                            fclose($handle);
                        } else {
                            echo "<tr><td colspan='5'>Error: Unable to open CSV file.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Log file not found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <?php
        // Check if the form is submitted
        if (isset($_POST['clear'])) {
            // Specify the path to your CSV file
            $csvFile = '/Applications/XAMPP/xamppfiles/htdocs/DETEK-AI/scan_results.csv';

            // Open the CSV file for writing
            if (($handle = fopen($csvFile, 'w')) !== false) {
                // Truncate the file to clear its contents
                ftruncate($handle, 0);

                // Close the file handle
                fclose($handle);

                echo "<script>alert('History cleared successfully');</script>";
            } else {
                echo "<script>alert('Error accessing history');</script>";
            }
        }
        ?>
    </body>

    </html>

<?php
} else {
    echo "<script>alert('Please Login to continue');
		</script>";
    header('location:logout.php');
}
?>