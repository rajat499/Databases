<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a>&emsp;&emsp;";
    echo "<a href='users.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>";

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

    echo "<b>Members in the Event<br></b>";
    $col = array("userid", "username", "email"); 
    $sql = $conn->query("SELECT user from participants where event='$event'");
    if(!$sql){
        echo "Error getting details of Participants. ".$conn->error."<br>";
        exit();
    }

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
        echo "No Participants in the event.<br>";
    }
?>
