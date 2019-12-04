<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }
    //echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a>&emsp;&emsp;";
    //echo "<a href='authenticate.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>";

	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";

    $event = $_GET["event"];
    if($event==""){
        echo "Event Not Specified<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    $sql = $conn->query("SELECT * from events_info where eventid='$event'");
    if(!$sql){
        echo "Error getting details of events. ".$conn->error."<br>";
        exit();
    }

    $result = $sql->fetch_assoc();
    if($result["eventmanager"] !== $user){
        echo "You are not a manager of this event.<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

echo"<div class='form-container'>";

  echo"<div>";
    echo "<a href='./edit_event.php?event=$event'>Edit Event Details</a><br><br>";
  echo"</div><br>";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['event_delete'])){
        $sql = $conn->query("DELETE FROM events_info WHERE eventid=$event");
        if(!$sql){
            echo "Error in query deleting the event $event: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            alert('Deleted the event with id: $event');
            window.location='authenticate.php';
        </script>";
    }

  echo"<div>";
    echo "<a href='events.php?event=$event'>Go to Events Page</a><br>";
  echo"</div><br>";

  echo"<div>";
    echo "<h1>Members in the Event</h1>";
    $col = array("username", "email"); 
    $sql = $conn->query("SELECT user from participants where event='$event'");
    if(!$sql){
        echo "Error getting details of Participants. ".$conn->error."<br>";
        exit();
    }

    if($sql->num_rows>0){
        echo "<table>";
        echo "<tr> <th>Username</th> <th>Email</th> <th>Remove</th> </tr>";

        while($row = $sql->fetch_assoc()){
            echo "<tr>";
                $row=$row["user"];
                $user = $conn->query("SELECT * from users_info WHERE userid='$row'");
                $user = $user->fetch_assoc();
                foreach($col as $att)
                    echo "<td>".$user[$att]."</td>";
                echo "<td><form action='' method='POST'><br>
                        <button class='button2' type='submit' value='$row' name='remove'>Remove</button>
                        </form></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else{
        echo "No Participants in the event.<br>";
    }
  echo"</div><br>";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove'])){
        delete_participants($_POST['remove'],$event);
    }

    function delete_participants($user, $event){
        include ("connection.php");
        $sql = $conn->query("DELETE FROM participants WHERE user='$user' AND event='$event'");
        if(!$sql){
            echo "Error in query delete participants: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            window.history.go(-1);
        </script>";
    }

  echo"<div>";
    echo "<h1>Approve/Delete Request</h1>";
    $col = array("username", "email"); 
    $sql = $conn->query("SELECT user from event_join_req where event='$event'");
    if(!$sql){
        echo "Error getting details of Join Requests. ".$conn->error."<br>";
        exit();
    }

    if($sql->num_rows>0){
        echo "<table>";
        echo "<tr> <th>Username</th> <th>Email</th> <th>Approve/Delete</th> </tr>";

        while($row = $sql->fetch_assoc()){
            echo "<tr>";
                $row=$row["user"];
                $user = $conn->query("SELECT * from users_info WHERE userid='$row'");
                $user = $user->fetch_assoc();
                foreach($col as $att)
                    echo "<td>".$user[$att]."</td>";
                echo "<td><form action='' method='POST'><br>
                        <button class='button2' type='submit' value='$row' name='approve'>Approve</button> &emsp;
                        <button class='button2' type='submit' value='$row' name='delete'>Delete</button> 
                        </form></td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    else{
        echo "No requests Pending.<br>";
    }
  echo"</div><br>";

  echo"<div class='end'>";
  echo "<form action='' method='POST'>
                        <button class='button' type='submit' value='$row' name='event_delete'>Delete Event</button>
                        </form>";
  echo"</div><br>";
echo"</div>";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete'])){
        delete_req($_POST['delete'],$event);
    }
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['approve'])){
        add_participant($_POST["approve"],$event);
        delete_req($_POST["approve"],$event);
    }

    function add_participant($user, $event){
        include ("connection.php");
        $sql = $conn->query("INSERT INTO participants values('$user', $event)");
        if(!$sql){
            echo "Error in query add: ".$conn->error." <br>";
            exit();
        }
    }

    function delete_req($user, $event){
        include ("connection.php");
        $sql = $conn->query("DELETE FROM event_join_req WHERE user='$user' AND event='$event'");
        if(!$sql){
            echo "Error in query delete: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            window.history.go(-1);
        </script>";
    }
?>


<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/manage_event.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Edit Event</title>
    </head>
</html>
