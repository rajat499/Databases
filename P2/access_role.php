<?php
	session_start();
    
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }

    echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
    echo "Welcome to SCC System."."<br>";
    echo "Your username is: ".$_SESSION['username']."<br>";
    echo "<h1>Choose Your Role</h1>";

    if($user=="sysadmn"){
        echo "<a href='sysadmn.php'>System Administrator</a><br>";
        echo "<a href='controller.php'>Controller</a><br>";
        echo "<a href='users.php'>Normal User</a><br>";
    }
    else if($user=="controller"){
        echo "<a href='controller.php'>Controller</a><br>";
        echo "<a href='users.php'>Normal User</a><br>";
    }else{
        echo "<a href='users.php'>Normal User</a><br>";
    }

?>
    