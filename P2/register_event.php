<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])){ 
        register_event($_POST['eventname'], $_POST["startdate"], $_POST["enddate"], $_POST["orgn"], $_SESSION["username"]);
    }


    function register_event($eventname, $startdate, $enddate, $orgnType, $eventmanager){
        include("connection.php");
        $sql =     "INSERT INTO events_info(eventname, eventstatus, startdate, enddate, orgnType, eventmanager) VALUES 
                    ('$eventname', 1, '$startdate', '$enddate', '$orgnType', '$eventmanager' )";
        $result = $conn->query($sql);
        if(!$result){
            echo "Error in query: ".$conn->error." <br>";
            exit();
        }
        echo
        "<script type='text/javascript'>
            alert('Registered the event: $eventname');
            window.location='authenticate.php';
        </script>";
    }
?>

<html>
    <head>
    <title> Register an event</title>
    </head>
    <body>
        <a href='users.php'>Go Back to Homepage</a> &emsp;&emsp;&emsp; <a href='logout.php'>Logout</a><br><br>
        <b> Register a New Event</b>
        <form action="register_event.php" method="POST">
            Event Name*:   &emsp; <input type="text" name="eventname"  required="required"><br>
			Start Date*:   &emsp; <input type="date" name="startdate"  required="required"><br>
            End Date*: &emsp; <input type="date" name="enddate"  required="required"><br>
            Organization Type*: &emsp; <select name="orgn" required>
                                        <option value="">None</option>
                                        <option value="Non-Profit Organization">Non-Profit Organization</option>
                                        <option value="Family">Family</option>
                                        <option value="Community">Community</option>
                                        <option value="Other">Other</option>
                                        </select><br>
            <input type="submit" value="Register" name="register">
        </form>
    </body>
</html>