<?php
	session_start();
    
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }
echo "<p style='font-family:Brush script mt; font-size:25px; color:white'>Welcome to SCC System.</p>";
    echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
   // echo "Welcome to SCC System."."<br>";
	
  //  echo "Your username is: ".$_SESSION['username']."<br>";
    echo "<h1>Choose Your Role</h1>";

    if($user=="sysadmn"){
       // echo "<a href='sysadmn.php'>System Administrator</a><br>";
	echo "<button onclick='window.location.href=".'"sysadmn.php"'."' class='sysadmn'>System Administrator</button><br><br>";
       // echo "<a href='controller.php'>Controller</a><br>";
	echo "<button onclick='window.location.href=".'"controller.php"'."' class='controller'>Controller</button><br><br>";
       // echo "<a href='users.php'>Normal User</a><br>";
	echo "<button onclick='window.location.href=".'"users.php"'."' class='user'>Normal User</button><br><br>";
    }
    else if($user=="controller"){
        //echo "<a href='controller.php'>Controller</a><br>";
	echo "<button onclick='window.location.href=".'"controller.php"'."' class='controller'>Controller</button><br><br>";
       // echo "<a href='users.php'>Normal User</a><br>";
	echo "<button onclick='window.location.href=".'"users.php"'."' class='user'>Normal User</button><br><br>";
    }else{
        //echo "<a href='users.php'>Normal User</a><br>";
	echo "<button onclick='window.location.href=".'"users.php"'."' class='user'>Normal User</button><br><br>";
    }

?>
    <html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/controller.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Access Role</title>
    </head>
</html>
