<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
    }

	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
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

    echo "<h1>Description of the event</h1>";
    echo "<div class='form-container'><font color=''>".$eventdetails["description"]."</font></div>";

    echo "<h1>Members in the Event<h1></b>";
    $col = array("username", "email"); 
    $sql = $conn->query("SELECT user from participants where event='$event'");
    if(!$sql){
        echo "Error getting details of Participants. ".$conn->error."<br>";
        exit();
    }
    echo "<div class='form-container'>";
        if($sql->num_rows>0){
            echo "<table>";
            echo "<tr> <th>Username</th> <th>Email</th></tr>";

            while($row = $sql->fetch_assoc()){
                echo "<tr>";

                    $row=$row["user"];
                    $user = $conn->query("SELECT * from users_info WHERE userid='$row'");
                    $user = $user->fetch_assoc();
                    $userid = $user["userid"];
                        echo "<td><a href='timeline.php?watch=$userid'>".$user['username']."($userid)</a></td>";
                        echo "<td>".$user['email']."</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else{
            echo "<label><font color='grey'>No Participants in the event</font></label>.<br>";
        }
    echo"</div>";

    echo "<h1>Posts in the Event<h1></b>";
    $sql = $conn->query("SELECT * from event_posts where event=$event ORDER BY time_stamp DESC");
    
        echo "<form action='event_post.php' method='POST' enctype='multipart/form-data'>";
            echo "New Post:<br><textarea name='content' placeholder='Content' rows='6' cols='80'></textarea><br><br>";
            echo "Image: <input type='file' name='img'><br>";
            echo "<input type='hidden' name='event' value='$event'>";
            echo "<button type='submit' name='upload_post'>Post</button>";
        echo "</form>";

        if(!$sql){
            echo "Error getting older posts<br>";
            exit();
        }
        echo "<b>OLD POSTS</b><br>";
        // echo $sql->num_rows;
        while($post = $sql->fetch_assoc()){
            if($post["content"]=="" && $post["img"]=="")
                continue;
            echo "<div class='post'>";
                if($post["content"]!==""){
                    echo $post["content"]."<br>";
                }
                if($post["img"]!==""){
                    $path = $post["img"];
                    echo "<img src='$path' height='400' width='450' alt='$path'><br>";
                }
                echo "Posted by:". $post['user'] ."<br>";
                echo "<form action='view_post.php' method='POST'>";
                    echo "<input type='hidden' name='type' value='event'>";
                    echo "<button type='submit' name= 'view_post' value='".$post["postid"]."'>View this post</button>";
                echo "</form>";
            echo "</div>";
        }
    
?>

<html>
    <head>
	<link rel="stylesheet" type="text/css" href="css/events.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Events</title>
    </head>
</html>
