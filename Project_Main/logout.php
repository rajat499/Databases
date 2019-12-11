<?php
	session_start();
    session_destroy();
    // $_SESSION["logout"] = "You have been successfully logged out.<br>";
    header("location:login.php");
?>

<!-- Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca-->