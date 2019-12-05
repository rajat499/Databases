<?php
    session_start();

    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }

    echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a>&emsp;&emsp;";
    echo "<a href='authenticate.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>";

    if($user !== "sysadmn"){
        echo "You don't have permissions to visit this page.<br>";
        exit();
    }

    echo "Welcome to SCC System. You are a System Administrator."."<br>";
    echo "Your username is: ".$_SESSION["username"]."<br>";
    
    echo "<a href='sysadmn.php?show=user_details'>User Details</a><br>";
    echo "<a href='sysadmn.php?show=system_emails'>System Emails</a><br>";
    echo "<a href='sysadmn.php?show=event_details'>Events Details</a><br>";
    
    $show = $_GET["show"];
    if($show=="user_details"){
        include("user_details.php");
    }
    if($show=="system_emails"){
        include("system_emails.php");
    }
    if($show=="event_details"){
        include("event_details.php");
    }
?>