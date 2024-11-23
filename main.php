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
        <link rel="stylesheet" href="profile.css">
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

            <div class="left"> <i class="fa-regular fa-user fa-xl"></i>
                <?php echo  'Welcome, ' . $_SESSION['user_ses'];
                ?>
            </div>

        </div>


        <div class="container">

            <div class="one" id="myDiv">

                <div id="dragger">
                    <p>Scan Files </p>
                </div>
                <img src="file.png" alt="" height="300" px;>

            </div>


            <div class="two" id="scan1">
                <div id="dragger">
                    <p>Scan Folders</p>
                </div>
                <img src="folder.png" alt="" height="220" px;>


            </div>



        </div>
        <script>
            document.getElementById('myDiv').addEventListener('click', function() {
                window.location.href = 'uploadscan.php';
            });


            document.getElementById('scan1').addEventListener('click', function() {
                window.location.href = 'new.php';
            });
        </script>





    </body>

    </html>

<?php
} else {
    echo "<script>alert('Please Login to continue');
		</script>";
    header('location:logout.php');
}
?>