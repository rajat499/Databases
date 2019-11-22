<?php
	session_start();
    session_destroy();
    // $_SESSION["logout"] = "You have been successfully logged out.<br>";
    header("location:login.php");
?>