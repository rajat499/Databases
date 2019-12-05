<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }


    echo "Welcome to SCC System. You are a User."."<br>";
    
    
   		
    echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
    echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
    echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";
    echo "<button onclick='window.location.href=".'"MessagingSystem.php"'."'class='msg'>Messages</button>";
    echo "<button onclick='window.location.href=".'"timeline.php"'."'>Timeline</button>";
    echo "<button onclick='window.location.href=".'"edit_profile.php"'."'class='pro'>Edit Profile</button>";
    echo "<button onclick='window.location.href=".'"register_event.php"'."'class='reg'>Register Event</button><br><br>";
    
    $sql = "SELECT event from  participants where user='$user'";
    $result = $conn->query($sql);
    
    if(!$result){
        echo "Error getting details of events registered in. ".$conn->error."<br>";
    }
    $col = array( "startdate", "enddate"); 
    echo "<h1> Events you are registered for.</h1><br>";
    if($result->num_rows>0){
        echo "<table>";
        echo "<tr> <th>Event Name</th> <th>Start Date</th> <th>End Date</th> <th>Event Manager</th> <th>Withdraw</th></tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            $eventDet = $row["event"];
            $eventDet = $conn->query("SELECT * from events_info where eventid='$eventDet'");
            $eventDet = $eventDet->fetch_assoc();
            echo "<td><a href='events.php?event=".$eventDet["eventid"]."'>".$eventDet["eventname"]."</a></td>";
            foreach($col as $att)
                echo "<td>".$eventDet[$att]."</td>";
            $eventManager = $eventDet["eventmanager"];
            $eventManager = $conn->query("SELECT username from users_info where userid='$eventManager'");
            echo "<td>".$eventManager->fetch_assoc()["username"]."(".$eventDet["eventmanager"].")</td>";
            $event = $row["event"];
            echo "<td><a href='users.php?withdraw_event=true&event=$event'>Withdraw</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "You are not registered for any event.<br>";
    }

    if(isset($_GET['withdraw_event'])){ 
        withdraw_event($_SESSION["username"], $_GET['event']);
    }

    function withdraw_event($user, $event){
        include ("connection.php");
        $sql = $conn->query("DELETE FROM participants WHERE user='$user' AND event='$event'");
        if(!$sql){
            echo "Error in query: ".$conn->error." <br>";
        }
        echo 
        "<script type='text/javascript'>
            window.location='users.php';
        </script>";
    }

    $sql = "SELECT * from events_info where eventid NOT IN (SELECT event from participants where user='$user')";
    $result = $conn->query($sql);
    
    if(!$result){
        echo "Error getting details of events not registered in. ".$conn->error."<br>";
    }
    $col = array( "eventname", "startdate", "enddate"); 
    echo "<b><br><br> Click on any link to register for the event.</b><br>";
    if($result->num_rows>0){
        echo "<table>";
        echo "<tr><th>Event Name</th> <th>Start Date</th> <th>End Date</th> <th>Event Manager</th> <th>Register</th></tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            foreach($col as $att)
                echo "<td>".$row[$att]."</td>";
            $eventManager = $row["eventmanager"];
            $eventManager = $conn->query("SELECT username from users_info where userid='$eventManager'");
            echo "<td>".$eventManager->fetch_assoc()["username"]."(".$row["eventmanager"].")</td>";
            $check = $row["eventid"];
            $check = $conn->query("SELECT * from event_join_req where user='$user' AND event='$check'");
            if($check->num_rows>0){
                echo "<td>Your Request is already in process</td>";
            }else{
                $event = $row["eventid"];
                echo "<td><a href='users.php?join_event=true&event=$event'>Register</a></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "You are not registered for any event.<br>";
    }


    $sql = "SELECT * from events_info where eventmanager='$user'";
    $result = $conn->query($sql);
    
    if(!$result){
        echo "Error getting details of Managerial Roles. ".$conn->error."<br>";
    }
    $col = array( "startdate", "enddate", "orgnType", "debitaccno", "debitbankname"); 
    echo "<b><br> <br>You are manager of the following events. Click on Event ID to manage the event</b><br>";
    if($result->num_rows>0){
        echo "<table>";
        echo "<tr> <th>Event Name</th> <th>Start Date</th> <th>End Date</th> <th>Organization</th> <th>Debit Acc No</th> <th>Debit Bank</th></tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            $event = $row["eventid"];
            $eventname = $row["eventname"];
            echo "<td><a href='./manage_event.php?event=$event'>$eventname</a></td>";
            foreach($col as $att)
                echo "<td>".$row[$att]."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "You are not manager for any event.<br>";
    }
    
    if(isset($_GET['join_event'])){ 
        join_event($_SESSION["username"], $_GET['event']);
    }

    function join_event($user, $event){
        include ("connection.php");
        $sql = $conn->query("INSERT INTO event_join_req values('$user', $event)");
        if(!$sql){
            echo "Error in query: ".$conn->error." <br>";
        }
        echo 
        "<script type='text/javascript'>
            window.location='users.php';
        </script>";
    }

    $conn->close();
?>
<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/controller.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Events</title>
    </head>
</html>
