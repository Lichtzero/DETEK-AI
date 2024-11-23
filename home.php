<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DETEK-AI</title>
    <link rel="stylesheet" href="home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@500&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/80ebb5657c.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="d">
        <h1>DETEK-AI</h1>
    </div>

    <div class="container">


        <div class="login">
            <h4>Login</h4>
            <form action="" method="post">
                <i class="fa-solid fa-user" style="color: #ffffff"></i><input type="text" placeholder="Username" id="un" required="required" autocomplete="off" name="lun"><br>
                <i class="fa-solid fa-lock" style="color: #ffffff;"></i></i><input type="password" placeholder="Password" id="pass" required="required" name="lpass"><br>
                <input type="submit" value="Login in" name="login">
            </form>
            <p><a href="#">forgot password?</a></p>
        </div>


        <div class="signin">
            <h4>Sign In</h4>
            <form method="POST">
                <input type="text" placeholder="Username" name="un" required="required" autocomplete="off"><br>
                <input type="email" placeholder="Email" name="email" required="required" autocomplete="off"><br>
                <input type="password" placeholder="Password" name="ln" required="required" autocomplete="off"><br>
                <input type="password" placeholder="Confirm Password" name="pass" required="required"><br>
                <input type="submit" value="Sign in" name="submit">
            </form>

        </div>
    </div>




    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    function sendMail($email, $v_code)
    {
        require("phpmailer/Exception.php");
        require("phpmailer/PHPMailer.php");
        require("phpmailer/SMTP.php");



        $mail = new PHPMailer(true);
        try {
            //Server settings

            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = '***';                     //SMTP username
            $mail->Password   = '***';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('***', 'DETEK-AI');
            $mail->addAddress($email);     //Add a recipient


            //Content
            $mail->isHTML(true);

            header("Location: verify.php");                             //Set email format to HTML
            $mail->Subject = 'Email Verification Code';
            $mail->Body = "Your verification code is: $v_code";


            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    ?>

    <?php

    $con = mysqli_connect("localhost", "root", "", "deter");

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_POST['submit'])) {
        $u = $_POST['un'];
        $n = $_POST['email'];
        $pr = $_POST['pass'];
        $v_code = mt_rand(100000, 999999);

        $sel = "SELECT * FROM `user` WHERE username='$u'";
        $run = mysqli_query($con, $sel);
        $row = mysqli_num_rows($run);

        if ($row < 1) {
            $string1 = $_POST["ln"];
            $string2 = $_POST["pass"];
            if ($string1 === $string2) {
                $ins = "INSERT INTO `user`(`username`, `email`, `pasword`, `verification_code`) VALUES ('$u','$n','$pr','$v_code')";
                $run = mysqli_query($con, $ins);
                if ($run == true) {
                    if (($row < 1) && sendMail($_POST['email'], $v_code)) {

                        echo "<script>alert('Registered Sucessfully');
                window.open('home.php','_self');</script>";
                    }
                }
            } else {
                echo "<script>alert('Password not matched');
            window.open('home.php','_self');</script>";
            }
        } else {
            echo "<script>alert('Username already exists');
        window.open('home.php','_self');</script>";
        }
    }

    ?>


    <?php
    if (isset($_POST['login'])) {
        $con = mysqli_connect("localhost", "root", "", "deter");
        $us = $_POST['lun'];
        $ps = $_POST['lpass'];
        $sel = "SELECT * FROM `user` WHERE  username ='$us' AND pasword ='$ps'";
        $run = mysqli_query($con, $sel);
        $row = mysqli_num_rows($run);
        if ($row < 1) {
            echo "<script>alert('Incorrect password or username');</script>";
            echo "<script>window.open('demo.php','_self');</script>";
        } else {
            $data = mysqli_fetch_assoc($run);
            $name = $data['username'];
            session_start();
            $_SESSION['user_ses'] = $name;
            echo "<script>alert('Login successful');</script>";
            echo "<script>window.open('main.php','_self');</script>";
        }
    }
    ?>


</body>

</html>