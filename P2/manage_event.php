<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }
    echo "<a href='' onclick='window.history.go(-1); return false;'>Go to Previous page</a><br>";
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
    
    echo "<b>Approve/Delete Request<br></b>";
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
                echo "<td><form action='' method='POST'>
                        <button type='submit' value='$row' name='approve'>Approve</button> &emsp;
                        <button type='submit' value='$row' name='delete'>Delete</button> 
                        </form></td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    else{
        echo "No requests Pending.<br>";
    }


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