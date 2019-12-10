<?php
    session_start();

    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }
	echo "<p style='font-family:Brush script mt; font-size:25px; color:white'>SCC System.<br> You are a System Administrator</p>";
   // echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a>&emsp;&emsp;";
//    echo "<a href='authenticate.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>";
	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
    if($user !== "sysadmn"){
        echo "You don't have permissions to visit this page.<br>";
        exit();
    }

    //echo "Welcome to SCC System. You are a System Administrator."."<br>";
  
   // echo "Your username is: ".$_SESSION["username"]."<br>";
    
   // echo "<a href='sysadmn.php?show=user_details'>User Details</a><br>";
	echo "<button onclick='window.location.href=".'"sysadmn.php?show=user_details"'."' class='userdetails'>User Details</button><br><br>";
    //echo "<a href='sysadmn.php?show=system_emails'>System Emails</a><br>";
	echo "<button onclick='window.location.href=".'"sysadmn.php?show=system_emails"'."' class='emails'>System Emails</button><br><br>";
   // echo "<a href='sysadmn.php?show=event_details'>Events Details</a><br>";
    echo "<button onclick='window.location.href=".'"sysadmn.php?show=event_details"'."' class='eventdetails'>Events Details</button><br><br>";

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
<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/controller.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>System Administrator</title>
    </head>
</html>
