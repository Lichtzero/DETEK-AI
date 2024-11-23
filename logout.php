
	<?php
session_start();

$_SESSION["user_ses"]=$value;
unset($_SESSION["user_ses"]);
header('location:home.php');
?>