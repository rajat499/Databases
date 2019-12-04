<?php
	session_start();
    
    include("connection.php");
    $user = $_SESSION['username'];
    if($user==""){
        echo "Please Login to the system first<br>";
        echo "<a href='./login.php'>Go to Login</a><br>";
        exit();
    }

    if($user !== "sysadmn"){
        echo "You don't have permissions to visit this page.<br>";
        exit();
    }

    echo "<b>Details of Users in the System</b><br>";
    $sql = $conn->query("SELECT * from users_info");
    if(!$sql){
        echo "Error getting details of events. ".$conn->error."<br>";
        exit();
    }
    $col = array("userid", "username", "email");

    if($sql->num_rows>0){
        echo "<table>";
        echo "<tr> <th>UserID</th> <th>Name</th> <th>Email</th> <th>Delete User</th> </tr>";
        while($row = $sql->fetch_assoc()){
            echo "<tr>";
                foreach($col as $att)
                    echo "<td>".$row[$att]."</td>";
                echo "<td><form action='' method='POST'>
                        <button type='submit' value='".$row["userid"]."' name='remove_user'>Remove</button>
                        </form></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else{
        echo "No Participants in the event.<br>";
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove_user'])){
        delete_user($_POST['remove_user']);
    }

    function delete_user($user){
        include ("connection.php");
        $sql = $conn->query("DELETE FROM users_info WHERE userid='$user'");
        if(!$sql){
            echo "Error in query delete users: ".$conn->error." <br>";
            exit();
        }
        echo 
        "<script type='text/javascript'>
            window.history.go(-1);
        </script>";
    }

?>