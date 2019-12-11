<?php
	session_start();
?>
<!-- Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca -->
<?php 
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }

    if($user !== "sysadmn"){
        echo "You don't have permissions to visit this page.<br>";
        exit();
    }

    
    echo "<b>Details of Events</b><x>";
    $sql = $conn->query("SELECT * from events_info ORDER BY startdate DESC");
    if(!$sql){
        echo "Error getting events from the system. ".$conn->error."<br>";
        exit();
    }
    $col = array( "startdate", "enddate", "orgnType", "descr");
    
    $users = $conn->query("SELECT userid from users_info");
    if(!$users){
        echo "Error in getting detils of users";
        exit();
    }
    $managers = array();
    while($user = $users->fetch_assoc()){
        $managers[] = $user["userid"];
    }

    $count = $sql->num_rows;
    if($sql->num_rows>0){
        echo "<table>";
        echo "<tr> <th>Event Name</th> <th>Start Date</th> <th>End Date</th> <th>Organization</th> <th>Description</th> <th>Event Manager</th> <th>Change Manager</th> <th>Delete Event</th></tr>";
        while($row = $sql->fetch_assoc()){
            echo "<tr>";
                $eventname = $row["eventname"];
                $event = $row["eventid"];
                echo "<td><a href='manage_event.php?event=$event'>$eventname</a></td>";                
                foreach($col as $att)
                    echo "<td>".$row[$att]."</td>";
                
                echo "<form action='' method='POST'>";
                $eventmanager = $row["eventmanager"];
                echo "<td><select class='manager' placeholder='New Manager' name='manager'>";
                echo "<option value='$eventmanager'>$eventmanager</option>";
                    foreach($managers as $newmanager){
                        if($newmanager !== $eventmanager){
                            echo "<option value='$newmanager'>$newmanager</option>";
                        }
                    }
                echo "</td></select><br>";
                echo "<td><button type='submit' value='".$event."' name='change_manager'>Change Manager</button></td>
                    </form>";
                
                echo "<td><form action='' method='POST'>
                        <button type='submit' value='".$row["eventid"]."' name='remove_event'>Remove Event</button>
                        </form></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<h1>Toal number of events in the system: $count</h1>";
    }
    else{
        echo "No events in the system.<br>";
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['change_manager'])){
        $event = $_POST['change_manager'];
        $newmanager = $_POST['manager'];
        include ("connection.php");
        $sql = $conn->query("UPDATE events_info SET eventmanager='$newmanager' WHERE eventid='$event'");
        if(!$sql){
            echo "Error in changing manager: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            alert('Changed manager successfully to $newmanager');
            window.history.go(-1);
        </script>";
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove_event'])){
        delete_event($_POST['remove_event']);
    }

    function delete_event($id){
        include ("connection.php");
        $sql = $conn->query("DELETE FROM events_info WHERE eventid='$id'");
        if(!$sql){
            echo "Error in query delete emails: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            window.history.go(-1);
        </script>";
    }

?>