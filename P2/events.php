<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"users.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
    
    $event = $_GET["event"];
    if($event==""){
        echo "Event Not Specified<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    $eventdetails = $conn->query("SELECT * from events_info where eventid='$event'");
    if(!$eventdetails){
        echo "Error getting details of events. ".$conn->error."<br>";
        exit();
    }
    $eventdetails = $eventdetails->fetch_assoc();
    $participant = $conn->query("SELECT * from participants where event=$event AND user='$user'");

    if($participant->num_rows==0 && $user!==$eventdetails["eventmanager"] && $user!=="sysadmn"){
        echo "You have no rights to view this page. You are not a part of this event. Please register first.<br>";
        exit();
    }

    echo "<h1>Members in the Event<h1></b>";
    $col = array("userid", "username", "email"); 
    $sql = $conn->query("SELECT user from participants where event='$event'");
    if(!$sql){
        echo "Error getting details of Participants. ".$conn->error."<br>";
        exit();
    }
echo "<div class='form-container'>";
    if($sql->num_rows>0){
        echo "<table>";
        echo "<tr><th>User ID</th> <th>Username</th> <th>Email</th></tr>";

        while($row = $sql->fetch_assoc()){
            echo "<tr>";

                $row=$row["user"];
                $user = $conn->query("SELECT * from users_info WHERE userid='$row'");
                $user = $user->fetch_assoc();
                foreach($col as $att)
                    echo "<td>".$user[$att]."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else{
        echo "<label>No Participants in the event.</label><br>";
    }
echo"</div>";
?>

<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/events.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Events</title>
    </head>
</html>
