<?php
	session_start();
    
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
    <title>Groups</title>
    </head>
</html>

<?php
	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button><br><br>";

    $group = $_GET["group"];
    if($group==""){
        echo "<em style='font-family:Brush script mt; font-size:25px; color:white'> <br>Group Not Specified </em><br><br><br>";
        exit();
    }

    $groupdetails = $conn->query("SELECT * from groups_info where groupid=$group");
    if(!$groupdetails){
        echo "Error getting details of group. ".$conn->error."<br>";
        exit();
    }
    $groupdetails = $groupdetails->fetch_assoc();
    $participant = $conn->query("SELECT * from group_participants where groupid=$group AND user='$user'");

    if($participant->num_rows==0 && $user!==$groupdetails["groupmanager"] && $user!=="sysadmn"){
        echo "You have no rights to view this page. You are not a part of this group. Please register first.<br>";
        exit();
    }

	echo "<button onclick='window.location.href=".'"MessagingSystem.php"'."'class='msg'><i class='fa fa-comments'></i></button>";
	echo "<button onclick='window.history.go(-1); return false;' class='goBack'><i class='fa fa-arrow-left'></i></button>";
	echo "<button onclick='window.location.href=".'"authenticate.php"'."' class='btn'><i class='fa fa-home'></i></button>";
	echo "<button onclick='window.location.href=".'"logout.php"'."' class='logout'>Log out</button>";
	echo "<button onclick='window.location.href=".'"create_group.php?event='.$event.'"'."' class='create_group'><i class='fa fa-users'></i></button>";
    echo "<em style='font-family:Brush script mt; font-size:25px; color:white'>SCC System<br>You are ".$_SESSION['username']." and group is ". $groupdetails['groupname'] ."</em><br><br><br>";

    echo "<div id='topcontainer1'>";
    echo "<p class='headers'>Description of the Group</p>";
    echo "<div class='form-container'><font color=''>".$groupdetails["description"]."</font></div>";
    echo "</div>";

    echo "<div id='topcontainer2'>";
    echo "<p class='headers'>Members in the Group</p></b>";
    $col = array("username", "email"); 
    $sql = $conn->query("SELECT user from group_participants where groupid=$group");
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

    $user=$_SESSION['username'];

    echo"<div id='bottomcontainer'>";
    echo "<br><br><br><b>";
    $sql = $conn->query("SELECT * from group_posts where groupid=$group ORDER BY time_stamp DESC");
    echo "<div class='commenttextarea'>";
        echo "<form action='group_post.php' method='POST' enctype='multipart/form-data'>";
            echo "<input type='hidden' name='group' value='$group'>";
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
                                echo "<input type='hidden' name='type' value='group'><br><br>";
                                echo "<button class='button2' type='submit' name= 'view_post' value='".$post["postid"]."'>View</button>";
                            echo "</form>";
                        echo "</div>";
            echo"</div>";   
            echo"<br><br><br>";
        }
    echo "</div>";
?>



