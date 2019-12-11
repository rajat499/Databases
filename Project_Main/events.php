<?php
	session_start();
?>
<!-- Worked on by
40150463	Rajat Jaiswal r_jais@encs.concordia.ca

Integration of CSS by
27771223	Soumayyah AHMED	so_ahmed@encs.concordia.ca
40036565	Sadia Anowara Smitha s_smitha@encs.concordia.ca
40012133	Florin POENARIU f_poenar@encs.concordia.ca -->
<?php 
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "<h1>Please Login to the system first</h1><br>";
        include("login.php");
        exit();
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
<?php 
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

	echo "<button onclick='window.location.href=".'"MessagingSystem.php"'."'class='msg'><i class='fa fa-comments'></i></button>";
	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button>";
	echo "<button onclick='window.location.href=".'"create_group.php?event='.$event.'"'."' class='create_group'><i class='fa fa-users'></i></button>";
    echo "<em style='font-family:Brush script mt; font-size:25px; color:white'>SCC System<br>You are ".$_SESSION['username']." and event is ". $eventdetails['eventname'] ."</em><br><br><br>";

    echo "<div id='topcontainer1'>";
    echo "<p class='headers'>Description of the Event</p>";
    echo "<div class='form-container'><font color=''>".$eventdetails["descr"]."</font></div>";
    echo "</div>";

    echo "<div id='topcontainer2'>";
    echo "<p class='headers'>Members in the Event</p></b>";
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
    echo"</div>";
    
    $user=$_SESSION["username"];
    $sql = "SELECT groupid from  group_participants where user='$user' and event=$event";
    $result = $conn->query($sql);
    
    if(!$result){
        echo "Error getting details of groups already in. ".$conn->error."<br>";
    }
    $col = array( "description"); 
    echo "<p class='headers'> Groups you are part of.</p>";

    if($result->num_rows>0){
        echo "<table>";
        echo "<tr> <th>Group Name</th> <th>Description</th> <th>Group Manager</th> <th>Leave Group</th></tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            $groupDet = $row["groupid"];
            $groupDet = $conn->query("SELECT * from groups_info where groupid=$groupDet");
            $groupDet = $groupDet->fetch_assoc();
            echo "<td><a href='groups.php?group=".$groupDet["groupid"]."'>".$groupDet["groupname"]."</a></td>";
            foreach($col as $att)
                echo "<td>".$groupDet[$att]."</td>";
            $groupManager = $groupDet["groupmanager"];
            $groupm = $row["groupmanager"];
            $groupManager = $conn->query("SELECT username from users_info where userid='$groupManager'");
            echo "<td><a href='timeline.php?watch=$groupm'>".$groupManager->fetch_assoc()["username"]."(".$groupDet["groupmanager"].")</a></td>";
            $groupid = $row["groupid"];
            echo "<td><a href='events.php?withdraw_group=true&event=$event&groupid=$groupid'>Withdraw</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "You are not part of any group.<br>";
    }

    if(isset($_GET['withdraw_group'])){ 
        withdraw_group($_SESSION["username"], $_GET['groupid'], $_GET['event']);
    }

    function withdraw_group($user, $groupid, $event){
        include ("connection.php");
        $sql = $conn->query("DELETE FROM group_participants WHERE user='$user' AND groupid=$groupid");
        if(!$sql){
            echo "Error in query: ".$conn->error." <br>";
        }
        echo 
        "<script type='text/javascript'>
            window.location='events.php?event=$event';
        </script>";
    }

    $sql = "SELECT * from groups_info where event=$event AND groupid NOT IN (SELECT groupid from group_participants where user='$user' AND event=$event)";
    $result = $conn->query($sql);
    
    if(!$result){
        echo "Error getting details of groups not part of. ".$conn->error."<br>";
    }
    $col = array( "groupname", "description"); 
    echo "<b><br><br> <p class='headers'>Click on any link to register for the group.</p></b>";
    if($result->num_rows>0){
        echo "<table>";
        echo "<tr><th>Group Name</th> <th>Description</th> <th>Group Manager</th> <th>Register</th></tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            foreach($col as $att)
                echo "<td>".$row[$att]."</td>";
            $groupManager = $row["groupmanager"];
            $groupm = $row["groupmanager"];
            $groupManager = $conn->query("SELECT username from users_info where userid='$groupManager'");
            echo "<td><a href='timeline.php?watch=$groupm'>".$groupManager->fetch_assoc()["username"]."(".$row["groupmanager"].")</a></td>";
            $check = $row["groupid"];
            $check = $conn->query("SELECT * from group_join_req where user='$user' AND groupid=$check");
            if($check->num_rows>0){
                echo "<td>Your Request is already in process</td>";
            }else{
                $groupid = $row["groupid"];
                echo "<td><a href='events.php?join_group=true&event=$event&groupid=$groupid'> Join Group</a></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "You are part of all formed groups for this event.<br>";
    }


    $sql = "SELECT * from groups_info where groupmanager='$user'";
    $result = $conn->query($sql);
    
    if(!$result){
        echo "Error getting details of Managerial Roles. ".$conn->error."<br>";
    }
    $col = array("description"); 
    echo "<b><br> <br><p class='headers'>You are manager of the following groups. Click on Group name to manage the group</p></b>";
    if($result->num_rows>0){
        echo "<table>";
        echo "<tr> <th>Group Name</th> <th>Description</th> </tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            $groupid = $row["groupid"];
            $groupname = $row["groupname"];
            echo "<td><a href='manage_group.php?group=$groupid'>$groupname</a></td>";
            foreach($col as $att)
                echo "<td>".$row[$att]."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "You are not manager for any groups.<br>";
    }
    
    if(isset($_GET['join_group'])){ 
        join_group($_SESSION["username"], $_GET['groupid'], $_GET['event']);
    }

    function join_group($user, $groupid, $event){
        include ("connection.php");
        $sql = $conn->query("INSERT INTO group_join_req values('$user',$event, $groupid)");
        if(!$sql){
            echo "Error in query: ".$conn->error." <br>";
        }
        echo 
        "<script type='text/javascript'>
            window.location='events.php?event=$event';
        </script>";
    }

    echo"<div id='bottomcontainer'>";
    echo "<br><br><br><b>";
    $sql = $conn->query("SELECT * from event_posts where event=$event ORDER BY time_stamp DESC");
    echo "<div class='commenttextarea'>";
        echo "<form action='event_post.php' method='POST' enctype='multipart/form-data'>";
            echo "<input type='hidden' name='event' value='$event'>";
            //echo "<div id='topcontainer1";
            echo "New Post:<br><textarea class='comment' name='content' placeholder='Content' rows='6' cols='80'></textarea><br><br>";
            //echo "</div>";
            //echo "<div id='topcontainer2'>";
            echo "Image: <input type='file' name='img' style='margin-left:auto; margin-right:auto;'>";
            echo "<button class='postpost' type='submit' name='upload_post'>Post</button></div>";
            //echo "</div>";
        echo "</form>";

        if(!$sql){
            echo "Error getting older posts<br>";
            exit();
        }
        echo "<br><p class='headers'>POSTS</p><br>";
        // echo $sql->num_rows;
        while($post = $sql->fetch_assoc()){
            echo"<div class='postbox'>";
                        if($post["content"]=="" && $post["img"]=="")
                            continue;
                        echo "<div class='post'>";
                echo "<b style='color:blue'>". $post['user'] .": </b>";
                            if($post["content"]!==""){
                    echo "<small style='color:white; font-size:16px'>".$post["content"]."</small><br>";
                            }
                            if($post["img"]!==""){
                                $path = $post["img"];
                                echo "<img src='$path' height='400' width='450' alt='$path'><br>";
                            }
                            echo "<form action='view_post.php' method='POST'>";
                                echo "<input type='hidden' name='type' value='event'><br><br>";
                                echo "<button class='button2' type='submit' name= 'view_post' value='".$post["postid"]."'>View</button>";
                            echo "</form>";
                        echo "</div>";
            echo"</div>";   
            echo"<br><br><br>";
        }
        echo "</div>";
?>


