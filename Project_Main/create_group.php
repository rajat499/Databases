<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }

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

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['create_group'])){
        $groupname = $_POST["groupname"];
        $description = $_POST["description"];
        $q = $conn->query("INSERT INTO groups_info(groupname, description, event, groupmanager) VALUES('$groupname', '$description', '$event', '$user')");
        if(!$q){
            echo "Error in creating group: ".$conn->error." <br>";
            exit();
        }
        echo
        "<script type='text/javascript'>
            alert('Created the group: $groupname');
            window.location='events.php?event=$event';
        </script>";
    }

?>

<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/edit_event.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Create a Group</title>
    </head>
<body>
	<button onclick="window.location.href='authenticate.php'" class="btn"><i class="fa fa-home"></i></button> </b>
    <button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>
	<button onclick="window.location.href='logout.php'" class="logout">Log out</button><br><br>
  <h1>Create a Group</h1>
  <div class="form-container">
    <form action="" method="POST">
  	<div class='first'>
      <b>Group Details</b><br><br>
	    <input type="text" name="groupname" placeholder="Group Name" required="required"><br>
        <label>Description of the Group:</label><br><textarea name="description" rows='6' cols='25'></textarea><br>
        <br>
	    <input class="button" type="submit" value="Create Group" name="create_group"/>
	</div>
     </form>
  </div>
</body>
</html>
