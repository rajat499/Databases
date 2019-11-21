<?php
	session_start();
    echo "Welcome to SCC System. You are a Controller."."<br>";
    echo "Your username is: ".$_SESSION['username']."<br>";
    echo "Your password is: ".$_SESSION['password']."<br>";
?>