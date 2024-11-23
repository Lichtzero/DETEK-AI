<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="gh.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@500&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0px;
            padding: 0px;
        }

        body {
            background-color: hsl(226, 61%, 23%);
        }

        .container {
            border: 2px solid;
            width: 470px;
            margin: auto;
            margin-top: 150px;
            height: 390px;
        }

        h2 {
            color: azure;
            font-size: 31px;
            padding: 40px 0px 30px 100px;
        }

        p {
            font-size: 20px;
            padding: 40px 0px 30px 20px;
            color: whitesmoke;
        }

        label {
            padding-left: 20px;
            color: whitesmoke;
        }

        button {
            width: 180px;
            height: 40px;
            border-radius: 20px;
            font-size: 20px;
            margin-top: 20px;
            margin-left: 130px;
            border: 2px solid #A9D1FF;
            cursor: pointer;
            font-family: 'Oxanium', cursive;
            background-color: #A9D1FF;
        }

        .container input[type="text"] {
            width: 422.26px;
            height: 43.93px;
            margin-top: 20px;
            margin-left: 20px;
            /* border:2px solid; */
            /* border-top: none;border-left: none;border-right: none; */
            /* background-color:hsl(226, 61%, 23%); */
            font-family: 'Oxanium', cursive;
            outline: none;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Email Verification</h2>
        <p>Please enter the verification code sent to your email:</p>
        <form action="" method="GET">
            <label for="code">Verification Code: <?php isset($_GET['email']) ?></label><br>
            <input type="text" id="code" name="code" required="required" autocomplete="off"><br>
            <button type="submit">Verify</button>
        </form>
    </div>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "deter");

    // Verify the user using the verification code
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["code"])) {
        $userEnteredCode = $_GET["code"];

        $stmt = $conn->prepare("SELECT id FROM user WHERE verification_code = ? AND is_verified = 0");
        $stmt->bind_param("s", $userEnteredCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Mark the user's email as verified in the database
            $row = $result->fetch_assoc();
            $userId = $row["id"];

            // Update the is_verified field
            $updateSql = "UPDATE user SET is_verified = 1 WHERE id = $userId";
            if ($conn->query($updateSql) === TRUE) {
                echo "<script>alert('Successfully registered');
                  window.open('home.php','_self');</script>";
            } else {
                echo "<script>alert('Error updating record: " . $conn->error . "');
                  window.open('verify.php','_self');</script>";
            }
        } else {
            echo "<script>alert('Enter valid verification code');
              window.open('verify.php','_self');</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

</body>

</html>