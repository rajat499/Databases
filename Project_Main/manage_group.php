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
	<link rel="stylesheet" type="text/css" href="css/manage_event.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Manage Group</title>
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

    $sql = $conn->query("SELECT * from groups_info where groupid=$group");
    if(!$sql){
        echo "Error getting details of Group. ".$conn->error."<br>";
        exit();
    }

    $result = $sql->fetch_assoc();
    if($result["groupmanager"] !== $user && $user!=="sysadmn"){
        echo "<em style='font-family:Brush script mt; font-size:25px; color:white'> <br>You are not a manager of this group.</em><br><br><br>";
        exit();
    }

    $event = $result["event"];

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['group_delete'])){
        $sql = $conn->query("DELETE FROM groups_info WHERE groupid=$group");
        if(!$sql){
            echo "Error in query deleting the group $event: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            alert('Deleted the group with id: $group');
            window.location='events.php?event=$event';
        </script>";
    }

    echo"<div class='form-container'>";

        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['group_delete'])){
            $sql = $conn->query("DELETE FROM groups_info WHERE groupid=$group");
            if(!$sql){
                echo "Error in query deleting the group $group: ".$conn->error." <br>";
                exit();
            }
            echo 
            "<script type='text/javascript'>
                alert('Deleted the group with id: $group');
                window.location='events.php?event=$event';
            </script>";
        }

        echo"<div>";
            echo "<a href='groups.php?group=$group'>Go to Groups Page</a><br>";
        echo"</div><br>";



        echo"<div>";
            echo "<h1>Members in the Group</h1>";
            $col = array( "username", "email"); 
            $sql = $conn->query("SELECT user from group_participants where groupid=$group");
            if(!$sql){
                echo "Error getting details of Participants. ".$conn->error."<br>";
                exit();
            }

            if($sql->num_rows>0){
                echo "<table>";
                echo "<tr><th>UserID</th> <th>Username</th> <th>Email</th> <th>Remove</th> </tr>";

                while($row = $sql->fetch_assoc()){
                    echo "<tr>";
                        $row=$row["user"];
                        $user = $conn->query("SELECT * from users_info WHERE userid='$row'");
                        $user = $user->fetch_assoc();
                        $userid = $user["userid"];
                        echo "<td><a href='timeline.php?watch=$userid'>$userid</a></td>";
                        foreach($col as $att)
                            echo "<td>".$user[$att]."</td>";
                        echo "<td><form action='' method='POST'><br>
                                <button class='button2' type='submit' value='$row' name='remove_group_participant'>Remove</button>
                                </form></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            else{
                echo "No Participants in the group.<br>";
            }
        echo"</div><br>";
        
        $user = $_SESSION["username"];

        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove_group_participant'])){
            delete_group_participants($_POST['remove_group_participant'], $group);
        }

        function delete_group_participants($user, $group){
            include ("connection.php");
            $sql = $conn->query("DELETE FROM group_participants WHERE user='$user' AND groupid=$group");
            if(!$sql){
                echo "Error in query delete participants from group: ".$conn->error." <br>";
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
            $sql = $conn->query("SELECT user from group_join_req where groupid=$group");
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
                                <button class='button2' type='submit' value='$row' name='approve_participant'>Approve</button> &emsp;
                                <button class='button2' type='submit' value='$row' name='delete_participant'>Delete</button> 
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
                                <button class='button' type='submit' value='$row' name='group_delete'>Delete Group</button>
                                </form>";
        echo"</div><br>";

    echo"</div>";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_participant'])){
        delete_req($_POST['delete_participant'],$group);
    }
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['approve_participant'])){
        add_participant($_POST["approve_participant"], $group, $event);
        delete_req($_POST["approve_participant"],$group);
    }

    function add_participant($user, $group, $event){
        include ("connection.php");
        $sql = $conn->query("INSERT INTO group_participants values('$user', $event, $group)");
        if(!$sql){
            echo "Error in query add to group: ".$conn->error." <br>";
            exit();
        }
    }

    function delete_req($user, $group){
        include ("connection.php");
        $sql = $conn->query("DELETE FROM group_join_req WHERE user='$user' AND groupid=$group");
        if(!$sql){
            echo "Error in query delete from group: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            window.history.go(-1);
        </script>";
    }
?>



